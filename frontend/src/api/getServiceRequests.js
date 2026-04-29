const API_BASE_URL = "http://localhost:8080";

export async function getServiceRequests() {
  const response = await fetch(`${API_BASE_URL}/api/service-requests/list`);

  const data = await response.json();

  if (!response.ok) {
    throw new Error("Failed to load service requests");
  }

  return data;
}