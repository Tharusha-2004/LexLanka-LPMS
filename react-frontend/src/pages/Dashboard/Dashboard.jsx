import { useState, useEffect } from 'react';
import { Row, Col, Card, Spinner, Table, Badge } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom';
import {
  FaUsers, FaBriefcase, FaGavel, FaCalendarAlt,
  FaArrowRight, FaClipboardList,
} from 'react-icons/fa';
import ClientService from '../../services/ClientService';
import CaseService from '../../services/CaseService';

// Status badge colours matching Case model enums
const statusVariant = {
  'Active':        { bg: 'success',   label: 'Active' },
  'Awaiting Trial':{ bg: 'warning',   label: 'Awaiting Trial' },
  'Appealed':      { bg: 'info',      label: 'Appealed' },
  'Closed':        { bg: 'secondary', label: 'Closed' },
};

function StatCard({ icon: Icon, value, label, gradient, iconBg }) {
  return (
    <Card className={`stat-card ${gradient}`}>
      <Card.Body className="d-flex align-items-center gap-3">
        <div className="stat-icon-wrapper" style={{ background: iconBg }}>
          <Icon />
        </div>
        <div>
          <div className="stat-value">{value}</div>
          <div className="stat-label">{label}</div>
        </div>
      </Card.Body>
    </Card>
  );
}

function Dashboard() {
  const navigate = useNavigate();
  const [clients, setClients]     = useState([]);
  const [cases, setCases]         = useState([]);
  const [loading, setLoading]     = useState(true);

  useEffect(() => {
    const fetchAll = async () => {
      try {
        const [clientRes, caseRes] = await Promise.all([
          ClientService.getAll(),
          CaseService.getAll(),
        ]);
        setClients(clientRes.data);
        setCases(caseRes.data);
      } catch (err) {
        console.error('Dashboard fetch error:', err);
      } finally {
        setLoading(false);
      }
    };
    fetchAll();
  }, []);

  const activeCases = cases.filter((c) => c.status === 'Active').length;

  // Count upcoming court dates (all dates in all cases that are in the future)
  const today = new Date();
  const upcomingDates = cases.reduce((acc, c) => {
    const future = (c.courtDates || []).filter(
      (d) => new Date(d.scheduledDate) >= today
    );
    return acc + future.length;
  }, 0);

  const recentCases = [...cases]
    .sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
    .slice(0, 5);

  if (loading) {
    return (
      <div className="loading-container">
        <Spinner animation="border" style={{ color: 'var(--primary)' }} />
        <span className="text-muted">Loading dashboard…</span>
      </div>
    );
  }

  return (
    <>
      <div className="page-header">
        <h1>Dashboard</h1>
        <p>Welcome back — here's your practice overview</p>
      </div>

      {/* Stat Cards */}
      <Row className="g-4 mb-4">
        <Col xs={12} sm={6} xl={3}>
          <StatCard
            icon={FaBriefcase}
            value={cases.length}
            label="Total Cases"
            gradient="stat-navy"
            iconBg="rgba(255,255,255,0.15)"
          />
        </Col>
        <Col xs={12} sm={6} xl={3}>
          <StatCard
            icon={FaUsers}
            value={clients.length}
            label="Total Clients"
            gradient="stat-blue"
            iconBg="rgba(255,255,255,0.15)"
          />
        </Col>
        <Col xs={12} sm={6} xl={3}>
          <StatCard
            icon={FaGavel}
            value={activeCases}
            label="Active Cases"
            gradient="stat-green"
            iconBg="rgba(255,255,255,0.15)"
          />
        </Col>
        <Col xs={12} sm={6} xl={3}>
          <StatCard
            icon={FaCalendarAlt}
            value={upcomingDates}
            label="Upcoming Court Dates"
            gradient="stat-amber"
            iconBg="rgba(255,255,255,0.15)"
          />
        </Col>
      </Row>

      {/* Recent Cases */}
      <Row className="g-4">
        <Col xs={12} lg={8}>
          <Card>
            <Card.Header>
              <div className="d-flex align-items-center gap-2">
                <FaClipboardList style={{ color: 'var(--primary-light)' }} />
                <h5 className="mb-0">Recent Cases</h5>
              </div>
              <button
                className="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                onClick={() => navigate('/cases')}
              >
                View All <FaArrowRight size={12} />
              </button>
            </Card.Header>
            <Card.Body>
              {recentCases.length === 0 ? (
                <div className="empty-state">
                  <FaBriefcase className="empty-state-icon" />
                  <h6>No cases yet</h6>
                  <p>Add your first case to see it here.</p>
                </div>
              ) : (
                <Table responsive hover className="table">
                  <thead>
                    <tr>
                      <th>Case No.</th>
                      <th>Client</th>
                      <th>Category</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    {recentCases.map((c) => {
                      const sv = statusVariant[c.status] || { bg: 'secondary', label: c.status };
                      return (
                        <tr key={c._id} style={{ cursor: 'pointer' }} onClick={() => navigate('/cases')}>
                          <td>
                            <span className="fw-semibold" style={{ color: 'var(--primary)' }}>
                              {c.caseNumber}
                            </span>
                          </td>
                          <td>{c.clientId?.fullName || '—'}</td>
                          <td>{c.caseCategory}</td>
                          <td>
                            <Badge
                              bg={sv.bg}
                              className="badge-pill-custom"
                            >
                              {sv.label}
                            </Badge>
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </Table>
              )}
            </Card.Body>
          </Card>
        </Col>

        {/* Quick Actions */}
        <Col xs={12} lg={4}>
          <Card style={{ height: '100%' }}>
            <Card.Header>
              <h5>Quick Actions</h5>
            </Card.Header>
            <Card.Body className="padded d-flex flex-column gap-3">
              <button
                className="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2"
                onClick={() => navigate('/clients/new')}
              >
                <FaUsers /> Add New Client
              </button>
              <button
                className="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2"
                onClick={() => navigate('/cases/new')}
              >
                <FaBriefcase /> Add New Case
              </button>
              <button
                className="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2"
                onClick={() => navigate('/clients')}
              >
                <FaUsers /> View All Clients
              </button>
              <button
                className="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2"
                onClick={() => navigate('/cases')}
              >
                <FaGavel /> View All Cases
              </button>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </>
  );
}

export default Dashboard;
