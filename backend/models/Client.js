const mongoose = require('mongoose');

const clientSchema = new mongoose.Schema({
  fullName: { 
    type: String, 
    required: true 
  },
  nicNumber: { 
    type: String, 
    required: true, 
    unique: true // Sri Lankan National Identity Card (must be unique)
  },
  email: { 
    type: String 
  },
  phone: { 
    type: String, 
    required: true 
  },
  address: { 
    type: String, 
    required: true 
  },
  clientType: {
    type: String,
    enum: ['Individual', 'Corporate'],
    default: 'Individual'
  }
}, { timestamps: true });

module.exports = mongoose.model('Client', clientSchema);