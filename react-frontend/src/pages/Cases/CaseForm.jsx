import { useState, useEffect } from 'react';
import {
  Card, Form, Button, Row, Col, Spinner,
  Toast, ToastContainer, Alert,
} from 'react-bootstrap';
import { useNavigate, useParams } from 'react-router-dom';
import { FaGavel, FaArrowLeft, FaSave } from 'react-icons/fa';
import CaseService from '../../services/CaseService';
import ClientService from '../../services/ClientService';

const INITIAL_FORM = {
  caseNumber:        '',
  clientId:          '',
  opposingPartyName: '',
  caseCategory:      'Civil',
  applicableLaw:     'General Law',
  courtLevel:        'District Court',
  status:            'Active',
};

function CaseForm() {
  const { id }   = useParams();
  const isEdit   = Boolean(id);
  const navigate = useNavigate();

  const [form, setForm]               = useState(INITIAL_FORM);
  const [clients, setClients]         = useState([]);
  const [loadingClients, setLoadingClients] = useState(true);
  const [loadingData, setLoadingData] = useState(isEdit);
  const [validated, setValidated]     = useState(false);
  const [submitting, setSubmitting]   = useState(false);
  const [toast, setToast]             = useState({ show: false, message: '', variant: 'success' });
  const [fetchError, setFetchError]   = useState('');

  // Load client dropdown
  useEffect(() => {
    const loadClients = async () => {
      try {
        const res = await ClientService.getAll();
        setClients(res.data);
      } catch (err) {
        setFetchError(`Could not load clients: ${err.message}`);
      } finally {
        setLoadingClients(false);
      }
    };
    loadClients();
  }, []);

  // Load case data in edit mode
  useEffect(() => {
    if (!isEdit) return;
    const loadCase = async () => {
      try {
        const res = await CaseService.getById(id);
        const d   = res.data;
        setForm({
          caseNumber:        d.caseNumber,
          clientId:          d.clientId?._id || d.clientId,
          opposingPartyName: d.opposingPartyName,
          caseCategory:      d.caseCategory,
          applicableLaw:     d.applicableLaw,
          courtLevel:        d.courtLevel,
          status:            d.status,
        });
      } catch (err) {
        setFetchError(`Could not load case: ${err.message}`);
      } finally {
        setLoadingData(false);
      }
    };
    loadCase();
  }, [id, isEdit]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formEl = e.currentTarget;
    if (!formEl.checkValidity()) {
      setValidated(true);
      return;
    }
    setSubmitting(true);
    try {
      if (isEdit) {
        await CaseService.update(id, form);
      } else {
        await CaseService.create(form);
      }
      setToast({
        show: true,
        message: isEdit ? 'Case updated successfully!' : 'Case created successfully!',
        variant: 'success',
      });
      setTimeout(() => navigate('/cases'), 1500);
    } catch (err) {
      setToast({ show: true, message: err.message, variant: 'danger' });
    } finally {
      setSubmitting(false);
    }
  };

  if (loadingData || loadingClients) {
    return (
      <div className="loading-container">
        <Spinner animation="border" style={{ color: 'var(--primary)' }} />
        <span className="text-muted">Loading…</span>
      </div>
    );
  }

  return (
    <>
      {/* Toast */}
      <ToastContainer position="top-end" className="p-3 toast-container">
        <Toast
          bg={toast.variant}
          show={toast.show}
          onClose={() => setToast((t) => ({ ...t, show: false }))}
          delay={3000}
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
          <h1>{isEdit ? 'Edit Case' : 'Add New Case'}</h1>
          <p>{isEdit ? 'Update case information below' : 'Register a new legal case'}</p>
        </div>
        <Button
          variant="outline-secondary"
          className="d-flex align-items-center gap-2"
          onClick={() => navigate('/cases')}
        >
          <FaArrowLeft size={13} /> Back to Cases
        </Button>
      </div>

      {fetchError && <Alert variant="danger" className="mb-4">{fetchError}</Alert>}

      <Card>
        <Card.Header>
          <div className="d-flex align-items-center gap-2">
            <FaGavel style={{ color: 'var(--primary-light)' }} />
            <h5>{isEdit ? 'Case Details' : 'New Case Details'}</h5>
          </div>
        </Card.Header>

        <Card.Body className="padded">
          <Form noValidate validated={validated} onSubmit={handleSubmit}>

            {/* Section: Case Identity */}
            <p className="text-muted fw-semibold mb-2" style={{ fontSize: '0.78rem', textTransform: 'uppercase', letterSpacing: '0.6px' }}>
              Case Identity
            </p>
            <Row className="g-3 mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="caseNumber">Case Number *</Form.Label>
                  <Form.Control
                    id="caseNumber"
                    type="text"
                    name="caseNumber"
                    placeholder="e.g. LK/CIV/2024/001"
                    value={form.caseNumber}
                    onChange={handleChange}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    Case number is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="clientId">Client *</Form.Label>
                  <Form.Select
                    id="clientId"
                    name="clientId"
                    value={form.clientId}
                    onChange={handleChange}
                    required
                  >
                    <option value="">— Select a client —</option>
                    {clients.map((cl) => (
                      <option key={cl._id} value={cl._id}>
                        {cl.fullName} ({cl.nicNumber})
                      </option>
                    ))}
                  </Form.Select>
                  <Form.Control.Feedback type="invalid">
                    Please select a client.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>

            <Row className="g-3 mb-4">
              <Col md={12}>
                <Form.Group>
                  <Form.Label htmlFor="opposingPartyName">Opposing Party Name *</Form.Label>
                  <Form.Control
                    id="opposingPartyName"
                    type="text"
                    name="opposingPartyName"
                    placeholder="e.g. ABC Company Ltd."
                    value={form.opposingPartyName}
                    onChange={handleChange}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    Opposing party name is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>

            <div className="section-divider" />

            {/* Section: Classification */}
            <p className="text-muted fw-semibold mb-2" style={{ fontSize: '0.78rem', textTransform: 'uppercase', letterSpacing: '0.6px' }}>
              Classification
            </p>
            <Row className="g-3 mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="caseCategory">Case Category *</Form.Label>
                  <Form.Select
                    id="caseCategory"
                    name="caseCategory"
                    value={form.caseCategory}
                    onChange={handleChange}
                    required
                  >
                    <option>Civil</option>
                    <option>Criminal</option>
                    <option>Commercial</option>
                    <option>Fundamental Rights</option>
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="applicableLaw">Applicable Law</Form.Label>
                  <Form.Select
                    id="applicableLaw"
                    name="applicableLaw"
                    value={form.applicableLaw}
                    onChange={handleChange}
                  >
                    <option>General Law</option>
                    <option>Kandyan Law</option>
                    <option>Muslim Law</option>
                    <option>Thesawalamai</option>
                  </Form.Select>
                </Form.Group>
              </Col>
            </Row>

            <Row className="g-3 mb-4">
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="courtLevel">Court Level *</Form.Label>
                  <Form.Select
                    id="courtLevel"
                    name="courtLevel"
                    value={form.courtLevel}
                    onChange={handleChange}
                    required
                  >
                    <option>Primary Court</option>
                    <option>Magistrate Court</option>
                    <option>District Court</option>
                    <option>High Court</option>
                    <option>Court of Appeal</option>
                    <option>Supreme Court</option>
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="status">Status</Form.Label>
                  <Form.Select
                    id="status"
                    name="status"
                    value={form.status}
                    onChange={handleChange}
                  >
                    <option>Active</option>
                    <option>Awaiting Trial</option>
                    <option>Appealed</option>
                    <option>Closed</option>
                  </Form.Select>
                </Form.Group>
              </Col>
            </Row>

            {/* Actions */}
            <div className="d-flex align-items-center gap-3 pt-2" style={{ borderTop: '1px solid var(--border)' }}>
              <Button
                id="btn-submit-case"
                type="submit"
                disabled={submitting}
                className="d-flex align-items-center gap-2 px-4"
              >
                {submitting ? (
                  <>
                    <Spinner size="sm" animation="border" />
                    {isEdit ? 'Updating…' : 'Saving…'}
                  </>
                ) : (
                  <>
                    <FaSave size={14} />
                    {isEdit ? 'Update Case' : 'Save Case'}
                  </>
                )}
              </Button>
              <Button
                variant="outline-secondary"
                onClick={() => navigate('/cases')}
                disabled={submitting}
              >
                Cancel
              </Button>
            </div>
          </Form>
        </Card.Body>
      </Card>
    </>
  );
}

export default CaseForm;
