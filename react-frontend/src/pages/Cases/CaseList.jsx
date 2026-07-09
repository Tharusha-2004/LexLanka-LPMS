import { useState, useEffect } from 'react';
import { Card, Table, Badge, Button, Spinner, Toast, ToastContainer } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom';
import { FaBriefcase, FaPlus, FaEdit, FaInbox } from 'react-icons/fa';
import CaseService from '../../services/CaseService';

const statusConfig = {
  'Active':         { bg: 'success',   text: 'Active' },
  'Awaiting Trial': { bg: 'warning',   text: 'Awaiting Trial' },
  'Appealed':       { bg: 'info',      text: 'Appealed' },
  'Closed':         { bg: 'secondary', text: 'Closed' },
};

const categoryColors = {
  'Civil':              '#1a7a4a',
  'Criminal':           '#c0392b',
  'Commercial':         '#1565c0',
  'Fundamental Rights': '#7b2fbe',
};

function CaseList() {
  const navigate = useNavigate();
  const [cases, setCases]     = useState([]);
  const [loading, setLoading] = useState(true);
  const [toast, setToast]     = useState({ show: false, message: '', variant: 'success' });

  useEffect(() => {
    const fetchCases = async () => {
      setLoading(true);
      try {
        const res = await CaseService.getAll();
        setCases(res.data);
      } catch (err) {
        setToast({ show: true, message: `Failed to load cases: ${err.message}`, variant: 'danger' });
      } finally {
        setLoading(false);
      }
    };
    fetchCases();
  }, []);

  return (
    <>
      {/* Toast */}
      <ToastContainer position="top-end" className="p-3 toast-container">
        <Toast
          bg={toast.variant}
          show={toast.show}
          onClose={() => setToast((t) => ({ ...t, show: false }))}
          delay={4000}
          autohide
        >
          <Toast.Header>
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
          <h1>Cases</h1>
          <p>Track and manage all legal cases in your practice</p>
        </div>
        <Button
          id="btn-add-case"
          className="d-flex align-items-center gap-2"
          onClick={() => navigate('/cases/new')}
        >
          <FaPlus size={13} /> Add Case
        </Button>
      </div>

      {/* Cases Table Card */}
      <Card>
        <Card.Header>
          <div className="d-flex align-items-center gap-2">
            <FaBriefcase style={{ color: 'var(--primary-light)' }} />
            <h5>All Cases</h5>
          </div>
          <span className="badge bg-light text-secondary border" style={{ fontSize: '0.78rem' }}>
            {cases.length} {cases.length === 1 ? 'record' : 'records'}
          </span>
        </Card.Header>

        <Card.Body>
          {loading ? (
            <div className="loading-container">
              <Spinner animation="border" style={{ color: 'var(--primary)' }} />
              <span className="text-muted">Fetching cases…</span>
            </div>
          ) : cases.length === 0 ? (
            <div className="empty-state">
              <FaInbox className="empty-state-icon" />
              <h6>No cases found</h6>
              <p>Get started by creating your first legal case.</p>
              <Button
                size="sm"
                className="mt-3 d-flex align-items-center gap-2"
                onClick={() => navigate('/cases/new')}
              >
                <FaPlus size={11} /> Add Case
              </Button>
            </div>
          ) : (
            <Table responsive hover className="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Case Number</th>
                  <th>Client</th>
                  <th>Category</th>
                  <th>Court Level</th>
                  <th>Status</th>
                  <th className="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                {cases.map((c, idx) => {
                  const sc = statusConfig[c.status] || { bg: 'secondary', text: c.status };
                  const catColor = categoryColors[c.caseCategory] || '#0A2540';
                  return (
                    <tr key={c._id}>
                      <td className="text-muted" style={{ width: '50px' }}>
                        {idx + 1}
                      </td>
                      <td>
                        <span className="fw-bold" style={{ color: 'var(--primary)', fontFamily: 'monospace' }}>
                          {c.caseNumber}
                        </span>
                      </td>
                      <td>
                        <span className="fw-semibold">{c.clientId?.fullName || '—'}</span>
                        {c.clientId?.nicNumber && (
                          <div style={{ fontSize: '0.75rem', color: 'var(--text-secondary)' }}>
                            {c.clientId.nicNumber}
                          </div>
                        )}
                      </td>
                      <td>
                        <span
                          className="badge-pill-custom"
                          style={{
                            background: `${catColor}18`,
                            color: catColor,
                            border: `1px solid ${catColor}40`,
                            display: 'inline-block',
                          }}
                        >
                          {c.caseCategory}
                        </span>
                      </td>
                      <td>
                        <span style={{ fontSize: '0.83rem', color: 'var(--text-secondary)' }}>
                          {c.courtLevel}
                        </span>
                      </td>
                      <td>
                        <Badge bg={sc.bg} className="badge-pill-custom">
                          {sc.text}
                        </Badge>
                      </td>
                      <td className="text-center">
                        <Button
                          id={`btn-edit-case-${c._id}`}
                          variant="outline-primary"
                          size="sm"
                          className="action-btn d-inline-flex align-items-center gap-1"
                          onClick={() => navigate(`/cases/${c._id}/edit`)}
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

export default CaseList;
