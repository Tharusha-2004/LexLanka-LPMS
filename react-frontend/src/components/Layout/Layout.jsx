import { Navbar, Nav, Container } from 'react-bootstrap';
import { NavLink } from 'react-router-dom';
import { FaGavel, FaTachometerAlt, FaUsers, FaBriefcase } from 'react-icons/fa';
import './Layout.css';

function Layout({ children }) {
  return (
    <div className="app-wrapper">
      <Navbar expand="lg" className="lexlanka-navbar" sticky="top">
        <Container fluid className="px-4">
          <Navbar.Brand as={NavLink} to="/" className="navbar-brand-custom">
            <FaGavel className="brand-icon" />
            <span className="brand-text">LexLanka</span>
            <span className="brand-subtitle">LPMS</span>
          </Navbar.Brand>

          <Navbar.Toggle
            aria-controls="main-navbar"
            className="navbar-toggler-custom"
          />

          <Navbar.Collapse id="main-navbar">
            <Nav className="ms-auto align-items-lg-center gap-1">
              <Nav.Link
                as={NavLink}
                to="/"
                end
                className={({ isActive }) =>
                  `nav-item-custom ${isActive ? 'active' : ''}`
                }
              >
                <FaTachometerAlt className="nav-icon" />
                Dashboard
              </Nav.Link>

              <Nav.Link
                as={NavLink}
                to="/clients"
                className={({ isActive }) =>
                  `nav-item-custom ${isActive ? 'active' : ''}`
                }
              >
                <FaUsers className="nav-icon" />
                Clients
              </Nav.Link>

              <Nav.Link
                as={NavLink}
                to="/cases"
                className={({ isActive }) =>
                  `nav-item-custom ${isActive ? 'active' : ''}`
                }
              >
                <FaBriefcase className="nav-icon" />
                Cases
              </Nav.Link>
            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>

      <main className="main-content">
        <Container fluid className="px-4 py-4">
          {children}
        </Container>
      </main>

      <footer className="app-footer">
        <Container fluid className="px-4">
          <span>© {new Date().getFullYear()} LexLanka LPMS — Legal Practice Management System</span>
        </Container>
      </footer>
    </div>
  );
}

export default Layout;
