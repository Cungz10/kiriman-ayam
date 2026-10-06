<script setup>
import { ref, onMounted } from 'vue'
import { MasterKirimanApi } from '../services/api'

const items = ref([])
const loading = ref(true)
const errorMsg = ref('')

const namaBaru = ref('')
const editId = ref(null)
const editNama = ref('')

async function muat() {
  loading.value = true
  try {
    const { data } = await MasterKirimanApi.list()
    items.value = data
  } catch (e) {
    errorMsg.value = 'Gagal memuat daftar kiriman.'
  } finally {
    loading.value = false
  }
}

async function tambah() {
  const nama = namaBaru.value.trim()
  if (!nama) return
  try {
    const { data } = await MasterKirimanApi.create(nama)
    items.value.push(data)
    namaBaru.value = ''
  } catch (e) {
    errorMsg.value = e?.response?.data?.message || 'Gagal menambah (mungkin sudah ada).'
  }
}

function mulaiEdit(item) {
  editId.value = item.id
  editNama.value = item.nama_kiriman
}

async function simpanEdit(item) {
  try {
    const { data } = await MasterKirimanApi.update(item.id, editNama.value.trim())
    Object.assign(item, data)
    editId.value = null
  } catch (e) {
    errorMsg.value = 'Gagal menyimpan perubahan.'
  }
}

async function hapus(item) {
  if (!confirm(`Hapus kiriman "${item.nama_kiriman}"?`)) return
  try {
    await MasterKirimanApi.remove(item.id)
    items.value = items.value.filter((i) => i.id !== item.id)
  } catch (e) {
    alert('Gagal menghapus, mungkin masih dipakai di riwayat.')
  }
}

onMounted(muat)
</script>

<template>
  <div class="max-w-md mx-auto">
    <div class="mb-5">
      <p class="font-mono text-xs text-amber-deep font-bold tracking-widest">MASTER DATA</p>
      <h1 class="text-2xl font-extrabold text-ink mt-1">Daftar Kiriman</h1>
    </div>

    <div class="card p-4 mb-4 flex gap-2">
      <input v-model="namaBaru" class="field-input" placeholder="Nama kiriman baru" @keyup.enter="tambah" />
      <button class="btn-amber whitespace-nowrap" @click="tambah">+ Tambah</button>
    </div>

    <p v-if="errorMsg" class="text-sm text-rust font-medium mb-3">{{ errorMsg }}</p>
    <p v-if="loading" class="text-sm text-muted">Memuat…</p>
    <div v-else-if="!items.length" class="card p-8 text-center text-muted">Belum ada data kiriman.</div>

    <div v-else class="space-y-2">
      <div v-for="item in items" :key="item.id" class="card p-3 flex items-center gap-2">
        <template v-if="editId === item.id">
          <input v-model="editNama" class="field-input flex-1" @keyup.enter="simpanEdit(item)" />
          <button class="btn-ghost !px-3 !py-1.5 text-xs" @click="simpanEdit(item)">Simpan</button>
          <button class="btn-ghost !px-3 !py-1.5 text-xs" @click="editId = null">Batal</button>
        </template>
        <template v-else>
          <p class="flex-1 font-medium">{{ item.nama_kiriman }}</p>
          <button class="btn-ghost !px-3 !py-1.5 text-xs" @click="mulaiEdit(item)">Edit</button>
          <button class="btn-danger !px-3 !py-1.5 text-xs" @click="hapus(item)">Hapus</button>
        </template>
      </div>
    </div>
  </div>
</template>
