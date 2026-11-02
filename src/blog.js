import { getCollection } from "astro:content";

// Newest first. Each post's id is its file name, which is also its URL slug.
export async function getPosts() {
  const posts = await getCollection("blog");
  return posts.sort((a, b) => b.data.date - a.data.date);
}

export function formatDate(date) {
  const pad = (n) => String(n).padStart(2, "0");
  return `${pad(date.getUTCMonth() + 1)}/${pad(date.getUTCDate())}/${date.getUTCFullYear()}`;
}
