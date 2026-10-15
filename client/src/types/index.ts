export type SupportedLocale = 'en' | 'am' | 'om';

export interface Language {
  id: number;
  code: SupportedLocale;
  name: string;
  direction: 'ltr' | 'rtl';
  is_default: boolean;
}

export interface MenuItemNode {
  id: number;
  parent_id: number | null;
  label: string;
  url: string;
  target?: string;
  icon?: string | null;
  sort_order: number;
  children?: MenuItemNode[];
}

export interface EmergencyContactItem {
  id: number;
  phone: string;
  email?: string;
  icon?: string;
  title: string;
  description: string;
}

export interface HeroSliderItem {
  id: number;
  image: string;
  link_url?: string;
  title: string;
  subtitle: string;
  button_text: string;
}

export interface GovernmentMemberItem {
  id: number;
  type: 'mayor' | 'cabinet' | 'council';
  photo?: string;
  email?: string;
  phone?: string;
  social_links?: Record<string, string>;
  name: string;
  title: string;
  bio: string;
}

export interface GovernmentDepartmentItem {
  id: number;
  parent_id?: number | null;
  type: 'structure' | 'sub_city' | 'office';
  image?: string;
  phone?: string;
  email?: string;
  name: string;
  description: string;
  address?: string;
  children?: { id: number; name: string; description: string }[];
}

export interface ServiceStepItem {
  id: number;
  step_number: number;
  icon?: string;
  title: string;
  description: string;
}

export interface ServiceItem {
  id: number;
  slug: string;
  type: 'text' | 'step';
  icon?: string;
  featured_image?: string;
  title: string;
  description: string;
  steps_count?: number;
  steps?: ServiceStepItem[];
}

export interface NewsItem {
  id: number;
  slug: string;
  featured_image?: string;
  is_featured: boolean;
  published_at?: string;
  views_count: number;
  title: string;
  excerpt: string;
  content?: string;
}

export interface EventItem {
  id: number;
  slug: string;
  featured_image?: string;
  start_date: string;
  end_date?: string;
  title: string;
  description: string;
  venue?: string;
}

export interface VacancyItem {
  id: number;
  slug: string;
  deadline: string;
  salary_range?: string;
  title: string;
  description: string;
  requirements?: string;
  department?: string;
  location?: string;
}

export interface TenderItem {
  id: number;
  slug: string;
  reference_number?: string;
  opening_date: string;
  closing_date: string;
  status: 'open' | 'closed' | 'awarded' | 'cancelled';
  attachment?: string;
  title: string;
  description: string;
}

export interface ExploreDestination {
  id: number;
  slug: string;
  featured_image?: string;
  name: string;
  description: string;
  category?: { name: string; slug: string };
}

export interface ExploreCategoryItem {
  id: number;
  slug: string;
  featured_image?: string;
  name: string;
  description: string;
  items: ExploreDestination[];
}

export interface ProgramItem {
  id: number;
  slug: string;
  featured_image?: string;
  start_date?: string;
  end_date?: string;
  budget?: number;
  progress_percentage?: number;
  status: 'planned' | 'ongoing' | 'completed' | 'suspended';
  title: string;
  description: string;
  objectives?: string;
}

export interface ResourceItem {
  id: number;
  slug: string;
  file_path: string;
  file_type: string;
  file_size: number;
  download_count: number;
  title: string;
  description?: string;
  filters?: { slug: string; name: string }[];
}

export interface PollOptionItem {
  id: number;
  text: string;
  votes: number;
}

export interface PollItem {
  id: number;
  slug: string;
  question: string;
  description?: string;
  total_votes: number;
  options: PollOptionItem[];
}

export interface ForumTopicItem {
  id: number;
  slug: string;
  title: string;
  content: string;
  author: string;
  views_count: number;
  replies_count: number;
  is_pinned: boolean;
  created_at: string;
}

export interface SearchResultItem {
  id: number;
  type: string;
  title: string;
  snippet: string;
  url: string;
}
