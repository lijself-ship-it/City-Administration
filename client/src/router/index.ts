import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import AboutView from '../views/AboutView.vue';
import GovernmentView from '../views/GovernmentView.vue';
import ServicesView from '../views/ServicesView.vue';
import MediaView from '../views/MediaView.vue';
import ExploreView from '../views/ExploreView.vue';
import ProgramsView from '../views/ProgramsView.vue';
import ResourcesView from '../views/ResourcesView.vue';
import ForumView from '../views/ForumView.vue';
import ContactView from '../views/ContactView.vue';
import AdminView from '../views/AdminView.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/about/:slug?',
    name: 'about',
    component: AboutView,
  },
  {
    path: '/government',
    name: 'government',
    component: GovernmentView,
  },
  {
    path: '/services/:slug?',
    name: 'services',
    component: ServicesView,
  },
  {
    path: '/media/:tab?',
    name: 'media',
    component: MediaView,
  },
  {
    path: '/explore/:slug?',
    name: 'explore',
    component: ExploreView,
  },
  {
    path: '/programs',
    name: 'programs',
    component: ProgramsView,
  },
  {
    path: '/resources',
    name: 'resources',
    component: ResourcesView,
  },
  {
    path: '/forum',
    name: 'forum',
    component: ForumView,
  },
  {
    path: '/contact',
    name: 'contact',
    component: ContactView,
  },
  {
    path: '/admin',
    name: 'admin',
    component: AdminView,
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(_to, _from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    return { top: 0, behavior: 'smooth' };
  },
});

export default router;
