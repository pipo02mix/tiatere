import { XMLParser } from "fast-xml-parser";

const FEED_URL = "https://medium.com/feed/@tiatere";
const LISTED_CATEGORIES = ["Post", "javascript", "erlang"];

let posts;

// Every page that needs posts calls this during the build, so the feed is
// downloaded once and shared.
export function getPosts() {
  posts ??= fetchPosts();
  return posts;
}

export async function getListedPosts(count) {
  const all = await getPosts();
  return all
    .filter((post) => post.categories.some((c) => LISTED_CATEGORIES.includes(c)))
    .slice(0, count);
}

async function fetchPosts() {
  const response = await fetch(FEED_URL);
  if (!response.ok) {
    throw new Error(`Fetching ${FEED_URL} failed with status ${response.status}`);
  }
  const feed = new XMLParser({ parseTagValue: false }).parse(await response.text());
  return [feed.rss.channel.item ?? []].flat().map(toPost);
}

function toPost(item) {
  return {
    title: item.title,
    slug: slugify(item.title),
    date: new Date(item.pubDate),
    categories: [item.category ?? []].flat(),
    content: item["content:encoded"]
      .replaceAll("<pre>", '<pre class="code"><code>')
      .replaceAll("</pre>", "</code></pre>"),
  };
}

function slugify(title) {
  const slug = title
    .normalize("NFKD")
    .replace(/[̀-ͯ]/g, "")
    .replace(/[^a-zA-Z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .toLowerCase();
  return slug || "n-a";
}

export function formatDate(date) {
  const pad = (n) => String(n).padStart(2, "0");
  return `${pad(date.getUTCMonth() + 1)}/${pad(date.getUTCDate())}/${date.getUTCFullYear()}`;
}

// Matches Twig's truncate(200, true): cut at the first space after the limit.
export function excerpt(html, length = 200) {
  const text = html.replace(/<[^>]*>/g, "");
  if (text.length <= length) return text;
  const cut = text.indexOf(" ", length);
  return cut === -1 ? text : `${text.slice(0, cut)}...`;
}
