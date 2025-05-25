/**
 * Utility function to get the correct base path for URLs based on environment
 * @param {string} path - The path to append to the base URL
 * @returns {string} The complete URL with the correct base path
 */
export function getUrl(path) {
  const basePath = import.meta.env.PROD ? '/cos30043/s103866373/project' : '';
  // Remove leading slash if path already has one
  const cleanPath = path.startsWith('/') ? path.slice(1) : path;
  return `${basePath}/${cleanPath}`;
} 