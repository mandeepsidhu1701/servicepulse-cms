import { useState } from "react";
import { submitServiceRequest } from "./api/serviceRequests";
import "./App.css";

function App() {
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

  function handleChange(e) {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
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

  return (
    <main className="page">
      <section className="card">
        <h1>ServicePulse Request Form</h1>
        <p>
          Submit a maintenance or service issue. Our team will review and assign
          it to the correct technician.
        </p>

        {message && <div className="message">{message}</div>}

        <form onSubmit={handleSubmit}>
          <label>
            Customer Name
            <input
              name="customerName"
              value={form.customerName}
              onChange={handleChange}
              required
            />
          </label>

          <label>
            Customer Email
            <input
              type="email"
              name="customerEmail"
              value={form.customerEmail}
              onChange={handleChange}
              required
            />
          </label>

          <label>
            Site / Location
            <input
              name="siteName"
              value={form.siteName}
              onChange={handleChange}
              required
            />
          </label>

          <label>
            Category
            <select
              name="category"
              value={form.category}
              onChange={handleChange}
              required
            >
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
            <select
              name="priority"
              value={form.priority}
              onChange={handleChange}
            >
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
              <option value="Urgent">Urgent</option>
            </select>
          </label>

          <label>
            Description
            <textarea
              name="description"
              value={form.description}
              onChange={handleChange}
              rows="5"
              required
            />
          </label>

          <button type="submit" disabled={loading}>
            {loading ? "Submitting..." : "Submit Request"}
          </button>
        </form>
      </section>
    </main>
  );
}

export default App;