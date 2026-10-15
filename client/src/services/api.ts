import axios from 'axios';
import type { SupportedLocale } from '../types';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 4000,
});

export const api = {
  // 1. Single roundtrip Init data
  async getInitData(locale: SupportedLocale) {
    try {
      const response = await apiClient.get('/init', { params: { lang: locale } });
      if (response.data?.success) {
        return response.data.data;
      }
    } catch {
      // Graceful fallback handled by Pinia store
    }
    return null;
  },

  // 2. Sliders
  async getHeroSliders(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/hero-sliders', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 3. Services
  async getServices(locale: SupportedLocale, type?: string) {
    try {
      const res = await apiClient.get('/services', { params: { lang: locale, type } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getServiceBySlug(slug: string, locale: SupportedLocale) {
    try {
      const res = await apiClient.get(`/services/${slug}`, { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 4. Media
  async getNews(locale: SupportedLocale, featured?: boolean) {
    try {
      const res = await apiClient.get('/news', { params: { lang: locale, featured: featured ? 1 : undefined } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getNewsDetail(slug: string, locale: SupportedLocale) {
    try {
      const res = await apiClient.get(`/news/${slug}`, { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getEvents(locale: SupportedLocale, filter?: string) {
    try {
      const res = await apiClient.get('/events', { params: { lang: locale, filter } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getVacancies(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/vacancies', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getTenders(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/tenders', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 5. Government
  async getGovernmentMembers(locale: SupportedLocale, type?: string) {
    try {
      const res = await apiClient.get('/government/members', { params: { lang: locale, type } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getGovernmentDepartments(locale: SupportedLocale, type?: string) {
    try {
      const res = await apiClient.get('/government/departments', { params: { lang: locale, type } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 6. Explore
  async getExplore(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/explore', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getExploreDetail(slug: string, locale: SupportedLocale) {
    try {
      const res = await apiClient.get(`/explore/${slug}`, { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 7. Transparency
  async getPrograms(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/programs', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async getResources(locale: SupportedLocale, filter?: string) {
    try {
      const res = await apiClient.get('/resources', { params: { lang: locale, filter } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  // 8. Poll Vote
  async votePoll(pollId: number, optionId: number) {
    const res = await apiClient.post(`/polls/${pollId}/vote`, { option_id: optionId });
    return res.data;
  },

  // 9. Forum
  async getForumTopics(locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/forum/topics', { params: { lang: locale } });
      return res.data?.data || null;
    } catch {
      return null;
    }
  },

  async createForumTopic(categoryId: number, title: string, content: string, locale: SupportedLocale) {
    const res = await apiClient.post('/forum/topics', {
      category_id: categoryId,
      title,
      content,
    }, { params: { lang: locale } });
    return res.data;
  },

  async replyForumTopic(topicId: number, content: string) {
    const res = await apiClient.post(`/forum/topics/${topicId}/reply`, { content });
    return res.data;
  },

  // 10. Contact Submit
  async submitContact(data: { name: string; email: string; phone?: string; subject: string; message: string }) {
    const res = await apiClient.post('/contact/submit', data);
    return res.data;
  },

  // 11. Global Search
  async search(query: string, locale: SupportedLocale) {
    try {
      const res = await apiClient.get('/search', { params: { q: query, lang: locale } });
      return res.data?.data || [];
    } catch {
      return [];
    }
  },

  // 12. Newsletter
  async subscribeNewsletter(email: string) {
    const res = await apiClient.post('/newsletter/subscribe', { email });
    return res.data;
  }
};
