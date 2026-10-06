<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { MasterKirimanApi } from '../services/api'

const router = useRouter()

const daftarKiriman = ref([])
const loading = ref(true)
const errorMsg = ref('')

const namaKiriman = ref('')
const kirimanBaru = ref('')
const mauTambahBaru = ref(false)
const nomerPo = ref('')
const presisi = ref(1) // 1 atau 2 angka di belakang koma

async function muatKiriman() {
  loading.value = true
  try {
    const { data } = await MasterKirimanApi.list()
    daftarKiriman.value = data
    if (data.length && !namaKiriman.value) {
      namaKiriman.value = data[0].nama_kiriman
    } else if (!data.length) {
      mauTambahBaru.value = true
    }
  } catch (e) {
    errorMsg.value = 'Gagal memuat daftar kiriman. Cek koneksi ke backend.'
  } finally {
    loading.value = false
  }
}

async function simpanKirimanBaru() {
  const nama = kirimanBaru.value.trim()
  if (!nama) return
  try {
    const { data } = await MasterKirimanApi.create(nama)
    daftarKiriman.value.push(data)
    namaKiriman.value = data.nama_kiriman
    kirimanBaru.value = ''
    mauTambahBaru.value = false
  } catch (e) {
    errorMsg.value = e?.response?.data?.message || 'Nama kiriman gagal disimpan (mungkin sudah ada).'
  }
}

function mulai() {
  errorMsg.value = ''
  if (!namaKiriman.value) {
    errorMsg.value = 'Pilih atau tambahkan nama kiriman dulu.'
    return
  }
  if (!nomerPo.value.trim()) {
    errorMsg.value = 'Nomor PO wajib diisi.'
    return
  }
  router.push({
    name: 'input',
    query: {
      nama_kiriman: namaKiriman.value,
      nomer_po: nomerPo.value.trim(),
      presisi: presisi.value,
    },
  })
}

const draftActive = ref(null)

onMounted(() => {
  muatKiriman()
  
  // Cek apakah ada draft di localStorage
  const saved = localStorage.getItem('draft_timbangan')
  if (saved) {
    try {
      const parsed = JSON.parse(saved)
      if (parsed.nilaiList && parsed.nilaiList.length > 0) {
        draftActive.value = parsed
      }
    } catch(e) {}
  }
})

function hapusDraft() {
  if (confirm('Yakin ingin menghapus sesi yang belum disimpan ini?')) {
    localStorage.removeItem('draft_timbangan')
    draftActive.value = null
  }
}

function lanjutkanDraft() {
  router.push({ name: 'input', query: { resume: '1' } })
}
</script>

<template>
  <div class="max-w-md mx-auto">
    <div v-if="draftActive" class="card p-4 mb-6 border border-amber/50 bg-amber/5">
      <div class="mb-2">
        <p class="font-bold text-ink text-sm">Ada Sesi Belum Disimpan</p>
        <p class="text-sm text-muted">
          {{ draftActive.nama_kiriman }} · PO: <span class="font-mono">{{ draftActive.nomer_po }}</span>
          <br>Terisi: <span class="font-bold">{{ draftActive.nilaiList.length }} data</span>
        </p>
      </div>
      <div class="flex gap-2 mt-3">
        <button class="btn-amber flex-1 py-2 text-sm" @click="lanjutkanDraft">Lanjutkan Sesi</button>
        <button class="btn-ghost py-2 text-sm" @click="hapusDraft">Hapus Draft</button>
      </div>
    </div>

    <div class="mb-6">
      <p class="font-mono text-xs text-amber-deep font-bold tracking-widest">MULAI SESI BARU</p>
      <h1 class="text-2xl font-extrabold text-ink mt-1">Input Timbangan Ayam</h1>
      <p class="text-sm text-muted mt-1">Pilih kiriman, isi nomor PO, lalu tentukan presisi angka sebelum mulai nimbang.</p>
    </div>

    <div class="card p-5 space-y-5">
      <div>
        <label class="field-label">Nama Kiriman</label>
        <div v-if="!mauTambahBaru" class="flex gap-2">
          <select v-model="namaKiriman" class="field-input" :disabled="loading">
            <option v-for="k in daftarKiriman" :key="k.id" :value="k.nama_kiriman">
              {{ k.nama_kiriman }}
            </option>
          </select>
          <button class="btn-ghost whitespace-nowrap" @click="mauTambahBaru = true">+ Baru</button>
        </div>
        <div v-else class="flex gap-2">
          <input
            v-model="kirimanBaru"
            class="field-input"
            placeholder="Nama kiriman baru"
            @keyup.enter="simpanKirimanBaru"
          />
          <button class="btn-amber whitespace-nowrap" @click="simpanKirimanBaru">Simpan</button>
          <button
            v-if="daftarKiriman.length"
            class="btn-ghost whitespace-nowrap"
            @click="mauTambahBaru = false"
          >
            Batal
          </button>
        </div>
      </div>

      <div>
        <label class="field-label">Nomor PO</label>
        <input
          v-model="nomerPo"
          class="field-input font-mono"
          placeholder="Mis. PO-2026-0708-01"
          @keyup.enter="mulai"
        />
      </div>

      <div>
        <label class="field-label">Presisi Angka</label>
        <div class="grid grid-cols-2 gap-2">
          <button
            class="btn px-4 py-3 border"
            :class="presisi === 1
              ? 'bg-ink text-paper border-ink'
              : 'bg-card text-ink border-ink/15 hover:border-ink/30'"
            @click="presisi = 1"
          >
            1 angka <span class="font-mono opacity-70">(4.1)</span>
          </button>
          <button
            class="btn px-4 py-3 border"
            :class="presisi === 2
              ? 'bg-ink text-paper border-ink'
              : 'bg-card text-ink border-ink/15 hover:border-ink/30'"
            @click="presisi = 2"
          >
            2 angka <span class="font-mono opacity-70">(4.15)</span>
          </button>
        </div>
        <p class="text-xs text-muted mt-2">
          1 angka pakai tombol cepat 4.1–6.0. 2 angka pakai geser slider biar gak perlu ngetik satu-satu.
        </p>
      </div>

      <p v-if="errorMsg" class="text-sm text-rust font-medium">{{ errorMsg }}</p>

      <button class="btn-amber w-full text-base py-3.5" @click="mulai">
        Mulai Input →
      </button>
    </div>
  </div>
</template>
