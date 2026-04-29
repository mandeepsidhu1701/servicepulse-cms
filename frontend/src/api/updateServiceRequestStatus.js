const API_BASE_URL = "http://localhost:8080";

export async function updateServiceRequestStatus(id, status) {
  const response = await fetch(`${API_BASE_URL}/api/service-requests/updateStatus`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id, status }),
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.error || "Failed to update status");
  }

  return data;
}