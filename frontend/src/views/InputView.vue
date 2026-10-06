<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { RiwayatInputApi } from '../services/api'

const route = useRoute()
const router = useRouter()

const namaKiriman = ref('')
const nomerPo = ref('')
const presisi = ref(1)

const nilaiList = ref([])
const sliderVal = ref(5.0)
const lastAdded = ref(null)

const saving = ref(false)
const savedOk = ref(false)
const errorMsg = ref('')

const tombolCepat = computed(() => {
  const arr = []
  for (let i = 41; i <= 60; i++) {
    arr.push(Math.round(i) / 10)
  }
  return arr
})

const total = computed(() => nilaiList.value.length)
const jumlahSum = computed(() => {
  if (!total.value) return 0
  const sum = nilaiList.value.reduce((a, b) => a + b, 0)
  return Math.round(sum * 100) / 100
})
const nilaiMax = computed(() => (total.value ? Math.max(...nilaiList.value) : 0))
const nilaiMin = computed(() => (total.value ? Math.min(...nilaiList.value) : 0))

function tambahNilai(v) {
  const val = Math.round(v * 100) / 100
  nilaiList.value.push(val)
  lastAdded.value = val
}

function hapusIndex(i) {
  nilaiList.value.splice(i, 1)
}

function undoTerakhir() {
  nilaiList.value.pop()
}

function resetSemua() {
  if (!nilaiList.value.length) return
  if (confirm('Hapus semua data yang sudah diinput di sesi ini?')) {
    nilaiList.value = []
    lastAdded.value = null
    localStorage.removeItem('draft_timbangan')
  }
}

async function simpanSelesai() {
  errorMsg.value = ''
  if (!nilaiList.value.length) {
    errorMsg.value = 'Belum ada data yang diinput.'
    return
  }
  saving.value = true
  try {
    await RiwayatInputApi.create({
      nama_kiriman: namaKiriman.value,
      nomer_po: nomerPo.value,
      nilai: nilaiList.value,
    })
    savedOk.value = true
    localStorage.removeItem('draft_timbangan')
  } catch (e) {
    errorMsg.value = e?.response?.data?.message || 'Gagal menyimpan ke server.'
  } finally {
    saving.value = false
  }
}

function inputBaru() {
  router.push({ name: 'start' })
}

onMounted(() => {
  const queryNama = route.query.nama_kiriman || ''
  const queryPo = route.query.nomer_po || ''
  const queryPresisi = route.query.presisi ? Number(route.query.presisi) : 1

  const draft = JSON.parse(localStorage.getItem('draft_timbangan') || 'null')

  if (route.query.resume === '1' && draft) {
    namaKiriman.value = draft.nama_kiriman
    nomerPo.value = draft.nomer_po
    presisi.value = draft.presisi
    nilaiList.value = draft.nilaiList || []
    return
  }

  if (queryNama && queryPo) {
    namaKiriman.value = queryNama
    nomerPo.value = queryPo
    presisi.value = queryPresisi

    if (draft && draft.nama_kiriman === queryNama && draft.nomer_po === queryPo) {
      nilaiList.value = draft.nilaiList || []
    }
  } else {
    router.replace({ name: 'start' })
  }
})

watch(
  [nilaiList, namaKiriman, nomerPo, presisi],
  () => {
    if (namaKiriman.value && nomerPo.value && !savedOk.value) {
      localStorage.setItem('draft_timbangan', JSON.stringify({
        nama_kiriman: namaKiriman.value,
        nomer_po: nomerPo.value,
        presisi: presisi.value,
        nilaiList: nilaiList.value,
      }))
    }
  },
  { deep: true }
)
</script>

<template>
  <div class="max-w-lg mx-auto pb-28">
    <div v-if="!savedOk">
      <div class="flex items-center justify-between mb-4">
        <div>
          <p class="font-mono text-xs text-amber-deep font-bold tracking-widest">{{ nomerPo }}</p>
          <h1 class="text-xl font-extrabold text-ink">{{ namaKiriman }}</h1>
        </div>
        <span class="text-xs font-mono bg-ink text-paper px-2 py-1 rounded">
          {{ presisi }} desimal
        </span>
      </div>

      <!-- Readout ala timbangan digital -->
      <div class="card px-6 py-8 mb-5 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
             style="background-image: repeating-linear-gradient(90deg, #1F2A44 0 1px, transparent 1px 8px);" />
        <p class="text-center text-[11px] uppercase tracking-[0.2em] text-muted mb-2">
          {{ presisi === 2 ? 'Nilai geser saat ini' : 'Nilai terakhir ditambahkan' }}
        </p>
        <p class="text-center font-mono font-bold text-ink leading-none"
           style="font-size: clamp(3rem, 12vw, 4.5rem);">
          {{ (presisi === 2 ? sliderVal : (lastAdded ?? 0)).toFixed(presisi) }}
          <span class="text-lg align-top text-muted">kg</span>
        </p>
      </div>

      <!-- Mode 1: tombol cepat -->
      <div v-if="presisi === 1" class="card p-4 mb-5">
        <p class="field-label">Tap nilai timbangan</p>
        <div class="grid grid-cols-5 gap-2">
          <button
            v-for="v in tombolCepat"
            :key="v"
            class="btn font-mono py-3 border border-ink/10 bg-paperdark/60 hover:bg-amber hover:text-ink hover:border-amber text-ink text-sm"
            @click="tambahNilai(v)"
          >
            {{ v.toFixed(1) }}
          </button>
        </div>
      </div>

      <!-- Mode 2: slider -->
      <div v-else class="card p-5 mb-5">
        <p class="field-label">Geser lalu tambahkan</p>
        <input
          type="range"
          min="4.10"
          max="6.00"
          step="0.01"
          v-model.number="sliderVal"
          class="w-full accent-amber h-2"
        />
        <div class="flex justify-between text-xs font-mono text-muted mt-1 mb-4">
          <span>4.10</span>
          <span>5.05</span>
          <span>6.00</span>
        </div>
        <button class="btn-amber w-full py-3" @click="tambahNilai(sliderVal)">
          + Tambahkan {{ sliderVal.toFixed(2) }} kg
        </button>
      </div>

      <!-- Stats live -->
      <div class="grid grid-cols-4 gap-2 mb-4">
        <div class="card py-3 text-center">
          <p class="text-[10px] uppercase text-muted tracking-wider">Total</p>
          <p class="font-mono font-bold text-lg">{{ total }}</p>
        </div>
        <div class="card py-3 text-center">
          <p class="text-[10px] uppercase text-muted tracking-wider">Sum</p>
          <p class="font-mono font-bold text-lg">{{ jumlahSum.toFixed(2) }}</p>
        </div>
        <div class="card py-3 text-center">
          <p class="text-[10px] uppercase text-muted tracking-wider">Max</p>
          <p class="font-mono font-bold text-lg text-leaf">{{ nilaiMax.toFixed(2) }}</p>
        </div>
        <div class="card py-3 text-center">
          <p class="text-[10px] uppercase text-muted tracking-wider">Min</p>
          <p class="font-mono font-bold text-lg text-rust">{{ nilaiMin.toFixed(2) }}</p>
        </div>
      </div>

      <!-- Daftar nilai -->
      <div v-if="nilaiList.length" class="mb-5">
        <p class="field-label">Data terinput ({{ total }})</p>
        <div class="flex flex-wrap gap-2">
          <span v-for="(v, i) in nilaiList" :key="i" class="stub">
            {{ v.toFixed(presisi) }}
            <button class="opacity-60 hover:opacity-100" @click="hapusIndex(i)">✕</button>
          </span>
        </div>
      </div>
      <p v-else class="text-sm text-muted mb-5">Belum ada data. Mulai tap tombol atau geser slider di atas.</p>

      <p v-if="errorMsg" class="text-sm text-rust font-medium mb-3">{{ errorMsg }}</p>

      <div class="flex gap-2">
        <button class="btn-ghost flex-1" :disabled="!nilaiList.length" @click="undoTerakhir">Undo</button>
        <button class="btn-danger flex-1" :disabled="!nilaiList.length" @click="resetSemua">Reset</button>
      </div>
    </div>

    <!-- Sukses -->
    <div v-else class="card p-8 text-center">
      <div class="w-14 h-14 rounded-full bg-leaf/15 text-leaf flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
      <h2 class="text-xl font-extrabold mb-1">Tersimpan</h2>
      <p class="text-sm text-muted mb-6">
        {{ total }} data dari {{ namaKiriman }} · {{ nomerPo }} berhasil dicatat.
        Total berat {{ jumlahSum.toFixed(2) }} kg.
      </p>
      <div class="flex gap-2 justify-center">
        <button class="btn-amber" @click="inputBaru">Input Baru</button>
        <button class="btn-ghost" @click="router.push({ name: 'riwayat' })">Lihat Riwayat</button>
      </div>
    </div>

    <!-- Tombol simpan mengambang -->
    <div v-if="!savedOk" class="fixed bottom-0 left-0 right-0 bg-paper/95 backdrop-blur border-t border-ink/10 p-4">
      <div class="max-w-lg mx-auto">
        <button class="btn-primary w-full py-3.5 text-base" :disabled="!nilaiList.length || saving" @click="simpanSelesai">
          {{ saving ? 'Menyimpan…' : `Simpan & Selesai (${total} data)` }}
        </button>
      </div>
    </div>
  </div>
</template>
