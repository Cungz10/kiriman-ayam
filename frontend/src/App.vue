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
  <div class="min-h-screen flex flex-col">
    <header class="border-b border-ink/10 bg-card/70 backdrop-blur sticky top-0 z-20">
      <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-md bg-ink text-amber font-mono font-bold text-sm flex items-center justify-center">
            KA
          </div>
          <div class="leading-tight">
            <p class="font-mono font-bold text-sm tracking-tight">TIMBANGAN AYAM</p>
            <p class="text-[11px] text-muted -mt-0.5">Yummy Choice</p>
          </div>
        </div>
        <nav class="flex items-center gap-1">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.to"
            class="px-3 py-2 rounded-md text-sm font-medium transition"
            :class="route.name === item.name ? 'bg-ink text-paper' : 'text-inksoft hover:bg-ink/5'"
          >
            {{ item.label }}
          </RouterLink>
        </nav>
      </div>
    </header>

    <main class="flex-1">
      <div class="max-w-3xl mx-auto px-4 py-6">
        <RouterView />
      </div>
    </main>

    <footer class="text-center text-xs text-muted py-6">
      Kiriman Ayam · dicatat langsung dari lapangan
    </footer>
  </div>
</template>
