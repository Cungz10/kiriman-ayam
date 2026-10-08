<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'

const route = useRoute()

const navItems = [
  { to: '/', label: 'Mulai', name: 'start' },
  { to: '/riwayat', label: 'Riwayat', name: 'riwayat' },
  { to: '/master-kiriman', label: 'Kiriman', name: 'master-kiriman' },
]
</script>

<template>
  <div class="min-h-screen flex flex-col relative overflow-hidden">
    <!-- Ambient Glassmorphism Atmospheric Color Spheres (Vibrant blurred gradient orbs) -->
    <!-- Top-Left: Warm Amber & Coral Glow -->
    <div
      class="fixed -top-24 -left-20 w-[480px] h-[480px] rounded-full blur-[90px] pointer-events-none -z-10 animate-pulse"
      style="background: radial-gradient(circle, rgba(251, 146, 60, 0.45) 0%, rgba(245, 158, 11, 0.3) 50%, transparent 75%); animation-duration: 8s;"
    />

    <!-- Top-Right / Middle: Electric Sky & Royal Indigo Glow -->
    <div
      class="fixed top-[15%] -right-28 w-[550px] h-[550px] rounded-full blur-[100px] pointer-events-none -z-10"
      style="background: radial-gradient(circle, rgba(56, 189, 248, 0.45) 0%, rgba(99, 102, 241, 0.35) 50%, transparent 75%);"
    />

    <!-- Center: Violet & Orchid Accent Glow -->
    <div
      class="fixed top-[45%] left-[10%] w-[460px] h-[460px] rounded-full blur-[95px] pointer-events-none -z-10 animate-pulse"
      style="background: radial-gradient(circle, rgba(168, 85, 247, 0.35) 0%, rgba(236, 72, 153, 0.25) 50%, transparent 75%); animation-duration: 10s;"
    />

    <!-- Bottom: Mint Teal & Emerald Glow -->
    <div
      class="fixed -bottom-32 right-[15%] w-[520px] h-[520px] rounded-full blur-[95px] pointer-events-none -z-10"
      style="background: radial-gradient(circle, rgba(52, 211, 153, 0.4) 0%, rgba(20, 184, 166, 0.25) 50%, transparent 75%);"
    />

    <!-- Glassmorphism Floating Top Header -->
    <header class="glass-header sticky top-0 z-30 transition-all">
      <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-800 to-slate-950 text-amber font-mono font-bold text-sm flex items-center justify-center shadow-[0_8px_16px_rgba(15,23,42,0.3),inset_0_1px_1px_rgba(255,255,255,0.4)] border border-slate-700/80"
          >
            KA
          </div>
          <div class="leading-tight">
            <p class="font-mono font-bold text-sm tracking-tight text-slate-800">TIMBANGAN AYAM</p>
            <p class="text-[11px] text-slate-500 font-medium">Sampling Pengiriman</p>
          </div>
        </div>

        <!-- Neumorphic-in-Glass Navigation Bar -->
        <nav class="flex items-center gap-1.5 p-1 bg-white/30 backdrop-blur-md rounded-2xl shadow-[inset_2px_2px_5px_rgba(100,116,139,0.18),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] border border-white/60">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.to"
            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-150"
            :class="route.name === item.name
              ? 'bg-white/85 text-slate-900 shadow-[0_4px_12px_rgba(31,38,135,0.12),inset_0_1px_1px_rgba(255,255,255,0.95)] border border-white/95'
              : 'text-slate-600 hover:text-slate-950 hover:bg-white/40'"
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

    <footer class="text-center text-xs text-slate-600/90 py-8 font-medium">
      Kiriman Ayam · Sistem Sampling Timbangan Digital
    </footer>
  </div>
</template>
