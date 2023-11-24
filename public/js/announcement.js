document.addEventListener('DOMContentLoaded', function () {
    var announcement = document.getElementById('announcement');

    function checkJobStatus() {
        // Get the job name from your application logic
        var jobName = endpoint;
        console.log("Test: " + jobName);

        // Make an AJAX request to check the job status
        fetch('/check-job-status/' + jobName)
            .then(response => response.json())
            .then(data => {
                if (data.jobDone) {
                    announcement.style.display = 'block';

                    setTimeout(function () {
                        announcement.style.display = 'none';
                    }, 5000); // 5000 milliseconds (5 seconds)
                } else {
                    // If the job is not done, check again after a delay
                    setTimeout(checkJobStatus, 5000); // 15000 milliseconds (15 seconds)
                }
            });
    }

    // Start checking the job status
    checkJobStatus();
});