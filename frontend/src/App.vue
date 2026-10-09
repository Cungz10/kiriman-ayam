<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'

const route = useRoute()
const logoFailed = ref(false)

const navItems = [
  { to: '/', label: 'Mulai', name: 'start' },
  { to: '/riwayat', label: 'Riwayat', name: 'riwayat' },
  { to: '/master-kiriman', label: 'Kiriman', name: 'master-kiriman' },
]
</script>

<template>
  <div class="min-h-screen flex flex-col relative overflow-hidden">
    <!-- Glassmorphism Floating Top Header -->
    <header class="glass-header sticky top-0 z-30 transition-all">
      <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-xl overflow-hidden shadow-[0_6px_16px_rgba(0,0,0,0.18),inset_0_1px_1px_rgba(255,255,255,0.9)] border border-white/80 bg-white/75 backdrop-blur-md flex items-center justify-center p-0.5"
          >
            <img
              v-if="!logoFailed"
              src="/logo.png"
              alt="Logo Timbangan Ayam"
              class="w-full h-full object-contain rounded-lg"
              @error="logoFailed = true"
            />
            <span
              v-else
              class="w-full h-full rounded-lg bg-gradient-to-br from-slate-800 to-slate-950 text-amber font-mono font-bold text-sm flex items-center justify-center"
            >
              KA
            </span>
          </div>
          <div class="leading-tight">
            <p class="font-mono font-bold text-sm tracking-tight text-slate-900">TIMBANGAN AYAM</p>
            <p class="text-[11px] text-slate-600 font-medium">Sampling Pengiriman</p>
          </div>
        </div>

        <!-- Neumorphic-in-Glass Navigation Bar -->
        <nav class="flex items-center gap-1.5 p-1 bg-white/40 backdrop-blur-md rounded-2xl shadow-[inset_2px_2px_5px_rgba(100,116,139,0.18),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] border border-white/70">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.to"
            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-150"
            :class="route.name === item.name
              ? 'bg-white/95 text-slate-900 shadow-[0_4px_12px_rgba(0,0,0,0.12),inset_0_1px_1px_rgba(255,255,255,1)] border border-white'
              : 'text-slate-700 hover:text-slate-950 hover:bg-white/50'"
          >
            {{ item.label }}
          </RouterLink>
        </nav>
      </div>
    </header>

    <main class="flex-1">
      <div class="max-w-3xl mx-auto px-4 py-8">
        <RouterView />
      </div>
    </main>

    <footer class="text-center text-xs text-white/90 py-8 font-medium drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
      Kiriman Ayam · Sistem Sampling Timbangan Digital
    </footer>
  </div>
</template>
