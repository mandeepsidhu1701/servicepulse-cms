import { useEffect, useState } from "react";
import { submitServiceRequest } from "./api/serviceRequests";
import { getServiceRequests } from "./api/getServiceRequests";
import { updateServiceRequestStatus } from "./api/updateServiceRequestStatus";
import "./App.css";

function App() {
  const [view, setView] = useState("form");
  const [requests, setRequests] = useState([]);
  const [form, setForm] = useState({
    customerName: "",
    customerEmail: "",
    siteName: "",
    category: "",
    priority: "Medium",
    description: "",
  });

  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  async function loadRequests() {
    const data = await getServiceRequests();
    setRequests(data);
  }

  useEffect(() => {
    if (view === "dashboard") {
      loadRequests();
    }
  }, [view]);

  function handleChange(e) {
    setForm({ ...form, [e.target.name]: e.target.value });
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);
    setMessage("");

    try {
      const result = await submitServiceRequest(form);
      setMessage(`Request submitted successfully. Request ID: ${result.id}`);
      setForm({
        customerName: "",
        customerEmail: "",
        siteName: "",
        category: "",
        priority: "Medium",
        description: "",
      });
    } catch (error) {
      setMessage(error.message);
    } finally {
      setLoading(false);
    }
  }

  async function handleStatusChange(id, status) {
  await updateServiceRequestStatus(id, status);
  await loadRequests();
}

  return (
    <main className="page">
      <section className="card">
        <div className="nav">
          <button onClick={() => setView("form")}>Request Form</button>
          <button onClick={() => setView("dashboard")}>Dashboard</button>
        </div>

        {view === "form" && (
          <>
            <h1>ServicePulse Request Form</h1>
            <p>Submit a maintenance or service issue.</p>

            {message && <div className="message">{message}</div>}

            <form onSubmit={handleSubmit}>
              <label>
                Customer Name
                <input name="customerName" value={form.customerName} onChange={handleChange} required />
              </label>

              <label>
                Customer Email
                <input type="email" name="customerEmail" value={form.customerEmail} onChange={handleChange} required />
              </label>

              <label>
                Site / Location
                <input name="siteName" value={form.siteName} onChange={handleChange} required />
              </label>

              <label>
                Category
                <select name="category" value={form.category} onChange={handleChange} required>
                  <option value="">Select category</option>
                  <option value="Electrical">Electrical</option>
                  <option value="Plumbing">Plumbing</option>
                  <option value="HVAC">HVAC</option>
                  <option value="Security">Security</option>
                  <option value="General Maintenance">General Maintenance</option>
                </select>
              </label>

              <label>
                Priority
                <select name="priority" value={form.priority} onChange={handleChange}>
                  <option value="Low">Low</option>
                  <option value="Medium">Medium</option>
                  <option value="High">High</option>
                  <option value="Urgent">Urgent</option>
                </select>
              </label>

              <label>
                Description
                <textarea name="description" value={form.description} onChange={handleChange} rows="5" required />
              </label>

              <button type="submit" disabled={loading}>
                {loading ? "Submitting..." : "Submit Request"}
              </button>
            </form>
          </>
        )}

        {view === "dashboard" && (
          <>
            <h1>Service Requests Dashboard</h1>
            <p>Live requests loaded from the Silverstripe PHP API.</p>

            <div className="request-list">
              {requests.map((item) => (
                <div className="request-card" key={item.id}>
                  <strong>#{item.id} — {item.siteName}</strong>
                  <span>{item.category} | {item.priority} | {item.status}</span>
                  <p>{item.description}</p>
                  <small>{item.customerName} — {item.customerEmail}</small>
                  <select
                    value={item.status}
                    onChange={(e) => handleStatusChange(item.id, e.target.value)}
                  >
                    <option value="New">New</option>
                    <option value="Assigned">Assigned</option>
                    <option value="InProgress">In Progress</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                  </select>
                </div>
              ))}
            </div>
          </>
        )}
      </section>
    </main>
  );
}

export default App;