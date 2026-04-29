const API_BASE_URL = "http://localhost:8080";

export async function submitServiceRequest(formData) {
  const response = await fetch(`${API_BASE_URL}/api/service-requests/submit`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formData),
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.error || "Failed to submit request");
  }

  return data;
}