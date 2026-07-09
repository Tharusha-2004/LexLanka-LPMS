import { useState, useEffect } from 'react';
import {
  Card, Form, Button, Row, Col, Spinner,
  Toast, ToastContainer, Alert,
} from 'react-bootstrap';
import { useNavigate, useParams } from 'react-router-dom';
import { FaUserPlus, FaArrowLeft, FaSave } from 'react-icons/fa';
import ClientService from '../../services/ClientService';

const INITIAL_FORM = {
  fullName:   '',
  nicNumber:  '',
  email:      '',
  phone:      '',
  address:    '',
  clientType: 'Individual',
};

function ClientForm() {
  const { id }    = useParams();       // present only in edit mode
  const isEdit    = Boolean(id);
  const navigate  = useNavigate();

  const [form, setForm]         = useState(INITIAL_FORM);
  const [validated, setValidated] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [loadingData, setLoadingData] = useState(isEdit);
  const [toast, setToast]       = useState({ show: false, message: '', variant: 'success' });
  const [fetchError, setFetchError] = useState('');

  // Load existing client data in edit mode
  useEffect(() => {
    if (!isEdit) return;
    const load = async () => {
      try {
        const res = await ClientService.getById(id);
        const { fullName, nicNumber, email, phone, address, clientType } = res.data;
        setForm({ fullName, nicNumber, email: email || '', phone, address, clientType });
      } catch (err) {
        setFetchError(`Could not load client: ${err.message}`);
      } finally {
        setLoadingData(false);
      }
    };
    load();
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
        await ClientService.update(id, form);
      } else {
        await ClientService.create(form);
      }
      setToast({
        show: true,
        message: isEdit ? 'Client updated successfully!' : 'Client added successfully!',
        variant: 'success',
      });
      setTimeout(() => navigate('/clients'), 1500);
    } catch (err) {
      setToast({ show: true, message: err.message, variant: 'danger' });
    } finally {
      setSubmitting(false);
    }
  };

  if (loadingData) {
    return (
      <div className="loading-container">
        <Spinner animation="border" style={{ color: 'var(--primary)' }} />
        <span className="text-muted">Loading client data…</span>
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
          <h1>{isEdit ? 'Edit Client' : 'Add New Client'}</h1>
          <p>{isEdit ? 'Update client information below' : 'Fill in the details to register a new client'}</p>
        </div>
        <Button
          variant="outline-secondary"
          className="d-flex align-items-center gap-2"
          onClick={() => navigate('/clients')}
        >
          <FaArrowLeft size={13} /> Back to Clients
        </Button>
      </div>

      {fetchError && <Alert variant="danger" className="mb-4">{fetchError}</Alert>}

      <Card>
        <Card.Header>
          <div className="d-flex align-items-center gap-2">
            <FaUserPlus style={{ color: 'var(--primary-light)' }} />
            <h5>{isEdit ? 'Client Details' : 'New Client Details'}</h5>
          </div>
        </Card.Header>
        <Card.Body className="padded">
          <Form noValidate validated={validated} onSubmit={handleSubmit}>
            {/* Row 1: Full Name + NIC */}
            <Row className="g-3 mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="fullName">Full Name *</Form.Label>
                  <Form.Control
                    id="fullName"
                    type="text"
                    name="fullName"
                    placeholder="e.g. Saman Perera"
                    value={form.fullName}
                    onChange={handleChange}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    Full name is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="nicNumber">NIC Number *</Form.Label>
                  <Form.Control
                    id="nicNumber"
                    type="text"
                    name="nicNumber"
                    placeholder="e.g. 199012345678 or 901234567V"
                    value={form.nicNumber}
                    onChange={handleChange}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    NIC number is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>

            {/* Row 2: Email + Phone */}
            <Row className="g-3 mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="email">Email Address</Form.Label>
                  <Form.Control
                    id="email"
                    type="email"
                    name="email"
                    placeholder="e.g. saman@example.com"
                    value={form.email}
                    onChange={handleChange}
                  />
                  <Form.Control.Feedback type="invalid">
                    Please enter a valid email.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label htmlFor="phone">Phone Number *</Form.Label>
                  <Form.Control
                    id="phone"
                    type="tel"
                    name="phone"
                    placeholder="e.g. 0771234567"
                    value={form.phone}
                    onChange={handleChange}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    Phone number is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
            </Row>

            {/* Row 3: Address + Client Type */}
            <Row className="g-3 mb-4">
              <Col md={8}>
                <Form.Group>
                  <Form.Label htmlFor="address">Address *</Form.Label>
                  <Form.Control
                    id="address"
                    as="textarea"
                    rows={2}
                    name="address"
                    placeholder="e.g. 123 Main Street, Colombo 07"
                    value={form.address}
                    onChange={handleChange}
                    required
                    style={{ resize: 'vertical' }}
                  />
                  <Form.Control.Feedback type="invalid">
                    Address is required.
                  </Form.Control.Feedback>
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group>
                  <Form.Label htmlFor="clientType">Client Type *</Form.Label>
                  <Form.Select
                    id="clientType"
                    name="clientType"
                    value={form.clientType}
                    onChange={handleChange}
                    required
                  >
                    <option value="Individual">Individual</option>
                    <option value="Corporate">Corporate</option>
                  </Form.Select>
                </Form.Group>
              </Col>
            </Row>

            {/* Actions */}
            <div className="d-flex align-items-center gap-3 pt-2" style={{ borderTop: '1px solid var(--border)' }}>
              <Button
                id="btn-submit-client"
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
                    {isEdit ? 'Update Client' : 'Save Client'}
                  </>
                )}
              </Button>
              <Button
                variant="outline-secondary"
                onClick={() => navigate('/clients')}
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

export default ClientForm;
