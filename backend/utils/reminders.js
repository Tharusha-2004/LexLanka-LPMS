const Case = require('../models/Case');

/**
 * Checks for cases that have a 'Trial' scheduled for tomorrow
 * and logs a reminder to the console.
 */
async function checkTomorrowTrials() {
  try {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Set to start of tomorrow
    tomorrow.setHours(0, 0, 0, 0);
    
    // Set to end of tomorrow
    const endOfTomorrow = new Date(tomorrow);
    endOfTomorrow.setHours(23, 59, 59, 999);

    // Find cases where there is at least one courtDate matching the criteria
    const cases = await Case.find({
      courtDates: {
        $elemMatch: {
          dateType: 'Trial',
          scheduledDate: {
            $gte: tomorrow,
            $lte: endOfTomorrow
          }
        }
      }
    }).populate('clientId');

    if (cases.length === 0) {
      console.log('No trials scheduled for tomorrow.');
      return;
    }

    console.log(`Found ${cases.length} trial(s) scheduled for tomorrow:`);
    
    cases.forEach(c => {
      // Find the specific court date that matches tomorrow
      const trialDate = c.courtDates.find(d => 
        d.dateType === 'Trial' && 
        new Date(d.scheduledDate) >= tomorrow && 
        new Date(d.scheduledDate) <= endOfTomorrow
      );
      
      const clientName = c.clientId ? c.clientId.fullName : 'Unknown Client';
      console.log(`- REMINDER: Case ${c.caseNumber} (${clientName}) has a Trial on ${trialDate.scheduledDate.toDateString()}. Notes: ${trialDate.notes || 'None'}`);
      
      // TODO: Replace console.log with SMS API Call
    });

  } catch (error) {
    console.error('Error checking for tomorrow\\'s trials:', error);
  }
}

module.exports = {
  checkTomorrowTrials
};
