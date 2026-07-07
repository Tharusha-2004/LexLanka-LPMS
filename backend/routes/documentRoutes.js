const express = require('express');
const router = express.Router();
const PDFDocument = require('pdfkit');
const Client = require('../models/Client');

// GET request to generate and download a legal proxy PDF for a client
router.get('/proxy/:clientId', async (req, res) => {
  try {
    const { clientId } = req.params;
    const client = await Client.findById(clientId);

    if (!client) {
      return res.status(404).json({ message: 'Client not found' });
    }

    // Create a new PDF document
    const doc = new PDFDocument({ margin: 50 });

    // Set headers so the browser treats it as a downloadable PDF file
    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename=Legal_Proxy_${client.fullName.replace(/\\s+/g, '_')}.pdf`);

    // Pipe the PDF directly to the HTTP response object
    doc.pipe(res);

    // Add content to the PDF
    doc.fontSize(24).font('Helvetica-Bold').text('LEGAL PROXY', { align: 'center' });
    doc.moveDown(2);
    
    doc.fontSize(14).font('Helvetica').text(`I, ${client.fullName}, bearing NIC ${client.nicNumber}, hereby appoint LexLanka as my legal representative.`, {
      align: 'left',
      lineGap: 5
    });

    // Finalize the PDF and end the stream
    doc.end();

  } catch (error) {
    console.error('Error generating PDF:', error);
    if (!res.headersSent) {
      res.status(500).json({ message: 'Internal Server Error while generating PDF' });
    }
  }
});

module.exports = router;
