<!DOCTYPE html>
<html>
<head>
    <title>Schedule Zoom Meeting</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.5.2/css/bootstrap.css" />
</head>
<body>
    <div class="container">
        <h1>Schedule a New Zoom Meeting</h1>
        <form id="schedule_meeting_form">
            <div class="form-group">
                <label for="topic">Meeting Topic</label>
                <input type="text" id="topic" name="topic" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (minutes)</label>
                <input type="number" id="duration" name="duration" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="agenda">Agenda</label>
                <textarea id="agenda" name="agenda" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Schedule Meeting</button>
        </form>
        <div id="notification" style="margin-top: 20px;"></div>

        <h2>Available Meetings</h2>
        <ul id="meetings_list" class="list-group"></ul>
    </div>

    <script>
        document.getElementById('schedule_meeting_form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/schedule-meeting', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                const notification = document.getElementById('notification');
                if (data.success) {
                    notification.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    loadMeetings(); // Reload the meetings list
                } else {
                    notification.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                const notification = document.getElementById('notification');
                notification.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                console.error('Full response:', error);
            });
        });

        function loadMeetings() {
            fetch('/meetings')
            .then(response => response.json())
            .then(data => {
                const meetingsList = document.getElementById('meetings_list');
                meetingsList.innerHTML = '';
                data.forEach(meeting => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.innerHTML = `<a href="/join-meeting/${meeting.meeting_id}" target="_blank">${meeting.topic}</a>`;
                    meetingsList.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error loading meetings:', error));
        }

        // Load meetings on page load
        document.addEventListener('DOMContentLoaded', loadMeetings);
    </script>
</body>
</html>