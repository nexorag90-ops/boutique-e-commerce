const API_BASE_URL = 'http://localhost:8000'

export async function apiFetch(endpoint, options = {}) {
  const isFormData = options.body instanceof FormData

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    credentials: 'include',
    headers: {
      ...(!isFormData && options.body
        ? { 'Content-Type': 'application/json' }
        : {}),
      ...(options.headers || {})
    }
  })

  const data = await response.json()

  if (!response.ok) {
    throw new Error(
      data.message || 'Une erreur est survenue.'
    )
  }

  return data
}