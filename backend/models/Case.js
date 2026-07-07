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
  opposingPartyName: {
    type: String,
    required: true
  },
  caseCategory: {
    type: String,
    enum: ['Civil', 'Criminal', 'Commercial', 'Fundamental Rights'],
    required: true
  },
  applicableLaw: {
    type: String,
    enum: ['General Law', 'Kandyan Law', 'Muslim Law', 'Thesawalamai'],
    default: 'General Law'
  },
  courtLevel: {
    type: String,
    enum: ['Primary Court', 'Magistrate Court', 'District Court', 'High Court', 'Court of Appeal', 'Supreme Court'],
    required: true
  },
  status: {
    type: String,
    enum: ['Active', 'Awaiting Trial', 'Appealed', 'Closed'],
    default: 'Active'
  },
  courtDates: [{
    dateType: {
      type: String,
      enum: ['Calling Date', 'Trial', 'Judgment'],
      required: true
    },
    scheduledDate: {
      type: Date,
      required: true
    },
    notes: {
      type: String
    }
  }]
}, { timestamps: true });

module.exports = mongoose.model('Case', caseSchema);
