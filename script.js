// JavaScript to handle button clicks and navigation based on batch selection
document.getElementById('get-results-btn').addEventListener('click', function() {
    const selectedBatch = document.getElementById('batch').value;
    if (selectedBatch === '21') {
        window.location.href = 'get_results.html';
    } else if (selectedBatch === '22') {
        window.location.href = 'get_results_22.html'; // Add another results page for batch 22
    } else if (selectedBatch === '23') {
        window.location.href = 'get_results_23.html'; // Add another results page for batch 23
    }
});

document.getElementById('update-results-btn').addEventListener('click', function() {
    const selectedBatch = document.getElementById('batch').value;
    if (selectedBatch === '21') {
        window.location.href = 'update_results.html';
    } else if (selectedBatch === '22') {
        window.location.href = 'update_results_22.html'; // Add another update page for batch 22
    } else if (selectedBatch === '23') {
        window.location.href = 'update_results_23.html'; // Add another update page for batch 23
    }
});
