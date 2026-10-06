import { createRouter, createWebHistory } from 'vue-router'
import StartView from '../views/StartView.vue'
import InputView from '../views/InputView.vue'
import HistoryView from '../views/HistoryView.vue'
import MasterKirimanView from '../views/MasterKirimanView.vue'

const routes = [
  { path: '/', name: 'start', component: StartView },
  { path: '/input', name: 'input', component: InputView },
  { path: '/riwayat', name: 'riwayat', component: HistoryView },
  { path: '/master-kiriman', name: 'master-kiriman', component: MasterKirimanView },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})
