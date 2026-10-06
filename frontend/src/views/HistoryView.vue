<script setup>
import { ref, onMounted, watch } from 'vue'
import { RiwayatInputApi } from '../services/api'

const items = ref([])
const meta = ref({ current_page: 1, last_page: 1 })
const loading = ref(true)
const errorMsg = ref('')

const filterNama = ref('')
const filterPo = ref('')
const page = ref(1)

const detail = ref(null)

async function muat() {
  loading.value = true
  errorMsg.value = ''
  try {
    const { data } = await RiwayatInputApi.list({
      nama_kiriman: filterNama.value || undefined,
      nomer_po: filterPo.value || undefined,
      page: page.value,
    })
    items.value = data.data
    meta.value = data
  } catch (e) {
    errorMsg.value = 'Gagal memuat riwayat. Cek koneksi ke backend.'
  } finally {
    loading.value = false
  }
}

async function hapus(item) {
  if (!confirm(`Hapus riwayat ${item.nama_kiriman} · ${item.nomer_po}?`)) return
  try {
    await RiwayatInputApi.remove(item.id)
    items.value = items.value.filter((i) => i.id !== item.id)
  } catch (e) {
    alert('Gagal menghapus.')
  }
}

function formatTanggal(t) {
  return new Date(t).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit',
  })
}

watch([filterNama, filterPo], () => {
  page.value = 1
  muat()
})
watch(page, muat)

onMounted(muat)
</script>

<template>
  <div>
    <div class="mb-5">
      <p class="font-mono text-xs text-amber-deep font-bold tracking-widest">RIWAYAT</p>
      <h1 class="text-2xl font-extrabold text-ink mt-1">Hasil Timbangan Tersimpan</h1>
    </div>

    <div class="card p-4 mb-4 flex flex-col sm:flex-row gap-3">
      <input v-model="filterNama" class="field-input" placeholder="Filter nama kiriman…" />
      <input v-model="filterPo" class="field-input font-mono" placeholder="Cari nomor PO…" />
    </div>

    <p v-if="errorMsg" class="text-sm text-rust font-medium mb-3">{{ errorMsg }}</p>
    <p v-if="loading" class="text-sm text-muted">Memuat…</p>

    <div v-else-if="!items.length" class="card p-8 text-center text-muted">
      Belum ada riwayat yang cocok.
    </div>

    <div v-else class="space-y-2">
      <div v-for="item in items" :key="item.id" class="card p-4">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="font-bold text-ink truncate">{{ item.nama_kiriman }}</p>
            <p class="font-mono text-xs text-muted">{{ item.nomer_po }} · {{ formatTanggal(item.created_at) }}</p>
          </div>
          <div class="flex gap-1 shrink-0">
            <button class="btn-ghost !px-3 !py-1.5 text-xs" @click="detail = item">Detail</button>
            <button class="btn-danger !px-3 !py-1.5 text-xs" @click="hapus(item)">Hapus</button>
          </div>
        </div>
        <div class="grid grid-cols-4 gap-2 mt-3">
          <div class="text-center">
            <p class="text-[10px] uppercase text-muted tracking-wider">Total</p>
            <p class="font-mono font-bold">{{ item.total_data }}</p>
          </div>
          <div class="text-center">
            <p class="text-[10px] uppercase text-muted tracking-wider">Sum</p>
            <p class="font-mono font-bold">{{ Number(item.rata_rata).toFixed(2) }}</p>
          </div>
          <div class="text-center">
            <p class="text-[10px] uppercase text-muted tracking-wider">Max</p>
            <p class="font-mono font-bold text-leaf">{{ Number(item.nilai_max).toFixed(2) }}</p>
          </div>
          <div class="text-center">
            <p class="text-[10px] uppercase text-muted tracking-wider">Min</p>
            <p class="font-mono font-bold text-rust">{{ Number(item.nilai_min).toFixed(2) }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="meta.last_page > 1" class="flex justify-center gap-2 mt-5">
      <button class="btn-ghost !px-3 !py-1.5 text-xs" :disabled="page <= 1" @click="page--">← Prev</button>
      <span class="text-xs text-muted self-center font-mono">{{ page }} / {{ meta.last_page }}</span>
      <button class="btn-ghost !px-3 !py-1.5 text-xs" :disabled="page >= meta.last_page" @click="page++">Next →</button>
    </div>

    <!-- Modal detail -->
    <div v-if="detail" class="fixed inset-0 bg-ink/40 flex items-end sm:items-center justify-center p-4 z-30" @click.self="detail = null">
      <div class="card p-5 w-full max-w-md max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between mb-3">
          <div>
            <p class="font-bold">{{ detail.nama_kiriman }}</p>
            <p class="font-mono text-xs text-muted">{{ detail.nomer_po }}</p>
          </div>
          <button class="text-muted hover:text-ink" @click="detail = null">✕</button>
        </div>
        <p class="field-label">Data mentah ({{ detail.total_data }})</p>
        <div class="flex flex-wrap gap-2">
          <span v-for="(v, i) in detail.data_input" :key="i" class="stub">{{ Number(v).toFixed(2) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
