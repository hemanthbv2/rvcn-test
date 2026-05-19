/**
 * ═══════════════════════════════════════════════════════════════
 * RVCN Chatbot — Google Apps Script (Google Sheets Integration)
 * ═══════════════════════════════════════════════════════════════
 * 
 * SETUP INSTRUCTIONS:
 * ───────────────────
 * 1. Go to https://sheets.google.com → Create a new Google Sheet
 * 2. Name it: "RVCN Chatbot Leads"
 * 3. In Row 1, add these headers (exactly):
 *    A1: Timestamp
 *    B1: Form Type
 *    C1: Name
 *    D1: Phone
 *    E1: Programme
 *    F1: Percentage / Specialization
 *    G1: City / Category
 *    H1: Extra Info
 * 
 * 4. Go to Extensions → Apps Script
 * 5. Delete any existing code and paste THIS ENTIRE FILE's contents
 * 6. Click "Deploy" → "New deployment"
 * 7. Choose type: "Web app"
 * 8. Set: 
 *    - Description: "RVCN Chatbot"
 *    - Execute as: "Me"
 *    - Who has access: "Anyone"
 * 9. Click "Deploy"
 * 10. Copy the Web App URL (it looks like: https://script.google.com/macros/s/xxxxx/exec)
 * 11. Paste that URL into chatbot-data.js → GOOGLE_SHEETS_URL variable
 * 
 * DONE! Form submissions will now appear in your Google Sheet.
 * ═══════════════════════════════════════════════════════════════
 */

// Handle POST requests from the chatbot
function doPost(e) {
  try {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    var data = JSON.parse(e.postData.contents);
    
    // Build the row based on form type
    var row = [
      new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' }), // Timestamp
      data.formType || '',      // Form Type (fee_enquiry, campus_visit, scholarship)
      data.name || '',           // Name
      data.phone || '',          // Phone
      data.programme || '',      // Programme
      data.extra1 || '',         // Percentage / Specialization
      data.extra2 || '',         // City / Category
      data.extra3 || ''          // Extra Info (time slot, etc.)
    ];
    
    sheet.appendRow(row);
    
    // Return success
    return ContentService
      .createTextOutput(JSON.stringify({ status: 'success', message: 'Data saved' }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({ status: 'error', message: error.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

// Handle GET requests (for testing)
function doGet(e) {
  return ContentService
    .createTextOutput(JSON.stringify({ status: 'ok', message: 'RVCN Chatbot Google Sheets API is running' }))
    .setMimeType(ContentService.MimeType.JSON);
}
