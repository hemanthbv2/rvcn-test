const endpoints = [
    {
        url: 'http://localhost:3000/send-fee-enquiry',
        payload: {
            formType: 'Fee Enquiry',
            name: 'Test Fee',
            phone: '1234567890',
            email: 'testfee@example.com',
            programme: 'B.Sc. Nursing',
            percentage: '85',
            city: 'Bangalore'
        }
    },
    {
        url: 'http://localhost:3000/send-scholarship',
        payload: {
            formType: 'Scholarship Guidance',
            name: 'Test Scholarship',
            phone: '1234567891',
            email: 'testscholar@example.com',
            programme: 'M.Sc. Nursing',
            category: 'OBC'
        }
    },
    {
        url: 'http://localhost:3000/send-campus-visit',
        payload: {
            formType: 'Campus Visit',
            name: 'Test Campus',
            phone: '1234567892',
            email: 'testcampus@example.com',
            timeSlot: '12:00 PM'
        }
    },
    {
        url: 'http://localhost:3000/send-counsellor',
        payload: {
            formType: 'Talk to Admission Counsellor',
            name: 'Test Counsellor',
            phone: '1234567893',
            email: 'testcounsellor@example.com'
        }
    },
    {
        url: 'http://localhost:3000/send-book-counselling',
        payload: {
            formType: 'Book Counselling Session',
            name: 'Test Book',
            phone: '1234567894',
            email: 'testbook@example.com',
            programme: 'M.Sc. Nursing',
            specialization: 'Medical Surgical'
        }
    }
];

async function runTests() {
    // Run twice for each endpoint to send 2 test emails each
    for (let i = 1; i <= 2; i++) {
        console.log(`\n--- Starting Batch ${i} ---`);
        for (const test of endpoints) {
            // Give them slightly different names to ensure they don't thread
            const dynamicPayload = {
                ...test.payload,
                name: `${test.payload.name} (Batch ${i})`
            };

            console.log(`Sending to ${test.url}...`);
            try {
                const response = await fetch(test.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dynamicPayload)
                });
                const data = await response.json();
                console.log(`Response from ${test.url}:`, data);
            } catch (error) {
                console.error(`Error sending to ${test.url}:`, error.message);
            }
        }
    }
}

runTests();
