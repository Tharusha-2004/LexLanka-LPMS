const mongoose = require('mongoose');

const caseSchema = new mongoose.Schema({
  caseNumber: {
    type: String,
    required: true,
    unique: true
  },
  clientId: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Client',
    required: true
  },
  caseCategory: {
    type: String,
    enum: ['Civil', 'Criminal'],
    required: true
  },
  applicableLaw: {
    type: String,
    enum: ['General Law', 'Kandyan Law', 'Muslim Law'],
    required: true
  },
  status: {
    type: String,
    enum: ['Active', 'Closed'],
    default: 'Active'
  }
}, { timestamps: true });

module.exports = mongoose.model('Case', caseSchema);
