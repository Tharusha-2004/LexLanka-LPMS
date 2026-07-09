import { useState, useEffect } from 'react';
import { Card, Table, Badge, Button, Spinner, Toast, ToastContainer } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom';
import { FaUsers, FaPlus, FaEdit, FaInbox } from 'react-icons/fa';
import ClientService from '../../services/ClientService';

const typeVariant = {
  'Individual': { bg: '#1565c0', text: '#e8f1fc' },
  'Corporate':  { bg: '#5c2d7c', text: '#f2e8fc' },
};

function ClientList() {
  const navigate = useNavigate();
  const [clients, setClients]     = useState([]);
  const [loading, setLoading]     = useState(true);
  const [toast, setToast]         = useState({ show: false, message: '', variant: 'success' });

  useEffect(() => {
    fetchClients();
  }, []);

  const fetchClients = async () => {
    setLoading(true);
    try {
      const res = await ClientService.getAll();
      setClients(res.data);
    } catch (err) {
      setToast({ show: true, message: `Failed to load clients: ${err.message}`, variant: 'danger' });
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      {/* Toast Notifications */}
      <ToastContainer position="top-end" className="p-3 toast-container">
        <Toast
          bg={toast.variant}
          show={toast.show}
          onClose={() => setToast((t) => ({ ...t, show: false }))}
          delay={4000}
          autohide
        >
          <Toast.Header closeButton>
            <strong className="me-auto">
              {toast.variant === 'success' ? '✅ Success' : '❌ Error'}
            </strong>
          </Toast.Header>
          <Toast.Body className="text-white">{toast.message}</Toast.Body>
        </Toast>
      </ToastContainer>

      {/* Page Header */}
      <div className="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <h1>Clients</h1>
          <p>Manage all registered clients in the system</p>
        </div>
        <Button
          id="btn-add-client"
          className="d-flex align-items-center gap-2"
          onClick={() => navigate('/clients/new')}
        >
          <FaPlus size={13} /> Add Client
        </Button>
      </div>

      {/* Client Table Card */}
      <Card>
        <Card.Header>
          <div className="d-flex align-items-center gap-2">
            <FaUsers style={{ color: 'var(--primary-light)' }} />
            <h5>All Clients</h5>
          </div>
          <span className="badge bg-light text-secondary border" style={{ fontSize: '0.78rem' }}>
            {clients.length} {clients.length === 1 ? 'record' : 'records'}
          </span>
        </Card.Header>

        <Card.Body>
          {loading ? (
            <div className="loading-container">
              <Spinner animation="border" style={{ color: 'var(--primary)' }} />
              <span className="text-muted">Fetching clients…</span>
            </div>
          ) : clients.length === 0 ? (
            <div className="empty-state">
              <FaInbox className="empty-state-icon" />
              <h6>No clients found</h6>
              <p>Get started by adding your first client.</p>
              <Button
                size="sm"
                className="mt-3 d-flex align-items-center gap-2"
                onClick={() => navigate('/clients/new')}
              >
                <FaPlus size={11} /> Add Client
              </Button>
            </div>
          ) : (
            <Table responsive hover className="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Full Name</th>
                  <th>NIC</th>
                  <th>Phone</th>
                  <th>Client Type</th>
                  <th className="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                {clients.map((client, idx) => {
                  const tv = typeVariant[client.clientType] || { bg: '#6b7a8d', text: '#fff' };
                  return (
                    <tr key={client._id}>
                      <td className="text-muted" style={{ width: '50px' }}>
                        {idx + 1}
                      </td>
                      <td>
                        <span className="fw-semibold" style={{ color: 'var(--primary)' }}>
                          {client.fullName}
                        </span>
                        {client.email && (
                          <div className="text-muted" style={{ fontSize: '0.78rem' }}>
                            {client.email}
                          </div>
                        )}
                      </td>
                      <td>
                        <code style={{ fontSize: '0.82rem', color: 'var(--text-secondary)' }}>
                          {client.nicNumber}
                        </code>
                      </td>
                      <td>{client.phone}</td>
                      <td>
                        <span
                          className="badge-pill-custom"
                          style={{
                            background: tv.bg,
                            color: tv.text,
                            display: 'inline-block',
                          }}
                        >
                          {client.clientType}
                        </span>
                      </td>
                      <td className="text-center">
                        <Button
                          id={`btn-edit-client-${client._id}`}
                          variant="outline-primary"
                          size="sm"
                          className="action-btn d-inline-flex align-items-center gap-1"
                          onClick={() => navigate(`/clients/${client._id}/edit`)}
                        >
                          <FaEdit size={12} /> Edit
                        </Button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </Table>
          )}
        </Card.Body>
      </Card>
    </>
  );
}

export default ClientList;
