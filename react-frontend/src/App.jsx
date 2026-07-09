import 'bootstrap/dist/css/bootstrap.min.css';
import './index.css';

import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Layout from './components/Layout/Layout';
import Dashboard from './pages/Dashboard/Dashboard';
import ClientList from './pages/Clients/ClientList';
import ClientForm from './pages/Clients/ClientForm';
import CaseList from './pages/Cases/CaseList';
import CaseForm from './pages/Cases/CaseForm';

function App() {
  return (
    <BrowserRouter>
      <Layout>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/clients" element={<ClientList />} />
          <Route path="/clients/new" element={<ClientForm />} />
          <Route path="/clients/:id/edit" element={<ClientForm />} />
          <Route path="/cases" element={<CaseList />} />
          <Route path="/cases/new" element={<CaseForm />} />
          <Route path="/cases/:id/edit" element={<CaseForm />} />
        </Routes>
      </Layout>
    </BrowserRouter>
  );
}

export default App;
