const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config();

// Initialize the Express App
const app = express();

// Middleware
app.use(cors());
app.use(express.json()); // Allows the server to accept JSON data

// Database Connection
mongoose.connect(process.env.MONGO_URI)
  .then(() => console.log('✅ Successfully connected to MongoDB Atlas!'))
  .catch((err) => console.error('❌ MongoDB connection error:', err));

// A simple test route
app.get('/api/test', (req, res) => {
  res.json({ message: 'Welcome to the LexLanka API!' });
});


// Import Routes
const clientRoutes = require('./routes/clientRoutes');
const caseRoutes = require('./routes/caseRoutes');
const documentRoutes = require('./routes/documentRoutes');
app.use('/api/clients', clientRoutes);
app.use('/api/cases', caseRoutes);
app.use('/api/documents', documentRoutes);

// Start the Server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`🚀 Server is running on http://localhost:${PORT}`);
});
