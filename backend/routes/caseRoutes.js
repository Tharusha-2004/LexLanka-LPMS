const express = require('express');
const router = express.Router();
const Case = require('../models/Case');

// POST route to create a new case
router.post('/', async (req, res) => {
  try {
    const newCase = new Case(req.body);
    const savedCase = await newCase.save();
    res.status(201).json(savedCase);
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
});

// GET route to fetch all cases with clientId populated
router.get('/', async (req, res) => {
  try {
    const cases = await Case.find().populate('clientId');
    res.json(cases);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

// PUT route to add a new court date to a specific case
router.put('/:id/add-date', async (req, res) => {
  try {
    const { id } = req.params;
    const { dateType, scheduledDate, notes } = req.body;

    const updatedCase = await Case.findByIdAndUpdate(
      id,
      { $push: { courtDates: { dateType, scheduledDate, notes } } },
      { new: true, runValidators: true }
    );

    if (!updatedCase) {
      return res.status(404).json({ message: 'Case not found' });
    }

    res.json(updatedCase);
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
});

module.exports = router;
