import axios from './api';

export const homeApi = {
  getHome: () =>
    axios.get('/api/home').then((res) => {
      const { hero_title, hero_subtitle, about_text } = res.data;
      return {
        heroTitle: hero_title,
        heroSubtitle: hero_subtitle,
        aboutText: about_text,
      };
    }),
};
