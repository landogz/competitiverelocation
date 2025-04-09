@extends('includes.app')

@section('title', 'Calendar')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Calendar</h4>
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary btn-sm me-2" id="syncGoogleCalendar">
                        <i class="fab fa-google me-1"></i> Sync Google Calendar
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="addEvent">
                        <i class="fas fa-plus me-1"></i> Add Event
                    </button>
                </div>                                
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Event Calendar</h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-today">Today</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                    <div style='clear:both'></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div><!-- container -->

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="event-date text-center p-3 bg-light rounded">
                            <h3 class="mb-0" id="eventDay">15</h3>
                            <p class="mb-0" id="eventMonth">Apr</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 id="eventTitle">Event Title</h4>
                        <p class="text-muted mb-1"><i class="far fa-clock me-2"></i> <span id="eventTime">9:00 AM - 10:00 AM</span></p>
                        <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i> <span id="eventLocation">Location</span></p>
                        <p class="text-muted mb-0"><i class="fas fa-user me-2"></i> <span id="eventOrganizer">Organizer</span></p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Description</h6>
                    <p id="eventDescription">Event description goes here.</p>
                </div>
                <div class="mb-3">
                    <h6>Attendees</h6>
                    <div id="eventAttendees" class="d-flex flex-wrap">
                        <!-- Attendees will be added here dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="eventGoogleLink" class="btn btn-primary" target="_blank">
                    <i class="fab fa-google me-1"></i> Open in Google Calendar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEventForm">
                    <div class="mb-3">
                        <label for="eventTitleInput" class="form-label">Event Title</label>
                        <input type="text" class="form-control" id="eventTitleInput" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="eventStartDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="eventStartDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="eventStartTime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="eventStartTime" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="eventEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="eventEndDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="eventEndTime" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="eventEndTime" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="eventLocationInput" class="form-label">Location</label>
                        <input type="text" class="form-control" id="eventLocationInput">
                    </div>
                    <div class="mb-3">
                        <label for="eventDescriptionInput" class="form-label">Description</label>
                        <textarea class="form-control" id="eventDescriptionInput" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="eventAttendeesInput" class="form-label">Attendees (comma separated emails)</label>
                        <input type="text" class="form-control" id="eventAttendeesInput">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEvent">Save Event</button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<!-- Google Calendar API -->
<script src="https://apis.google.com/js/api.js"></script>
<script src="https://accounts.google.com/gsi/client"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize FullCalendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'dayGridMonth,timeGridWeek,timeGridDay',
                center: 'title',
                right: ''
            },
            events: [], // Will be populated from Google Calendar
            eventClick: function(info) {
                // Prevent default behavior (redirection)
                info.jsEvent.preventDefault();
                openEventModal(info.event);
            },
            dateClick: function(info) {
                // Pre-fill the date in the add event form
                document.getElementById('eventStartDate').value = info.dateStr;
                document.getElementById('eventEndDate').value = info.dateStr;
                
                // Show the add event modal
                var addEventModal = new bootstrap.Modal(document.getElementById('addEventModal'));
                addEventModal.show();
            },
            eventDidMount: function(info) {
                // Add tooltips to events
                info.el.title = info.event.title;
                
                // Add custom styling to events
                info.el.classList.add('fc-event-modern');
            }
        });
        
        calendar.render();
        
        // Google Calendar API configuration
        const API_KEY = 'AIzaSyDfJAEOOESD7IvZy6qBYvOz7opcYVnDksw'; // Replace with your Google API key
        const CLIENT_ID = '677471639186-stq8l3ln4ag21k3thv9i7ll3kni37p5t.apps.googleusercontent.com'; // Replace with your Google Client ID
        const SCOPES = 'https://www.googleapis.com/auth/calendar';
        const DISCOVERY_DOCS = ['https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest'];
        const CALENDAR_ID = 'crsmoving08@gmail.com'; // Calendar ID
        
        let tokenClient;
        let gapiInited = false;
        let gisInited = false;
        let autoSyncInterval;
        
        // Initialize the Google API client
        function gapiLoaded() {
            console.log('gapi loaded');
            gapi.load('client', initializeGapiClient);
        }
        
        async function initializeGapiClient() {
            try {
                console.log('Initializing gapi client');
                await gapi.client.init({
                    apiKey: API_KEY,
                    discoveryDocs: DISCOVERY_DOCS,
                });
                gapiInited = true;
                console.log('gapi client initialized');
                maybeEnableButtons();
                
                // Try to authenticate and load calendar data immediately
                if (gisInited) {
                    console.log('Both APIs initialized, attempting to load calendar data');
                    attemptAutoLoad();
                }
            } catch (error) {
                console.error('Error initializing gapi client:', error);
            }
        }
        
        function gisLoaded() {
            console.log('gis loaded');
            try {
                // Get the current URL path
                const currentPath = window.location.pathname;
                console.log('Current path:', currentPath);
                
                // Create the token client with the current path as redirect URI
                tokenClient = google.accounts.oauth2.initTokenClient({
                    client_id: CLIENT_ID,
                    scope: SCOPES,
                    callback: '', // defined later
                    redirect_uri: window.location.origin + currentPath
                });
                
                gisInited = true;
                console.log('gis initialized with redirect URI:', window.location.origin + currentPath);
                maybeEnableButtons();
                
                // Try to authenticate and load calendar data immediately
                if (gapiInited) {
                    console.log('Both APIs initialized, attempting to load calendar data');
                    attemptAutoLoad();
                }
            } catch (error) {
                console.error('Error initializing gis:', error);
            }
        }
        
        function maybeEnableButtons() {
            console.log('Checking if buttons should be enabled:', { gapiInited, gisInited });
            if (gapiInited && gisInited) {
                document.getElementById('syncGoogleCalendar').disabled = false;
                console.log('Sync button enabled');
            }
        }
        
        // Function to attempt auto-loading of calendar data
        function attemptAutoLoad() {
            console.log('Attempting to auto-load calendar data');
            
            // Define the callback function
            tokenClient.callback = async (resp) => {
                console.log('Auth callback received:', resp);
                if (resp.error !== undefined) {
                    console.error('Auth error:', resp.error);
                    return;
                }
                
                try {
                    // Clear existing events
                    calendar.removeAllEvents();
                    console.log('Fetching events from Google Calendar');
                    
                    // Fetch events from Google Calendar
                    const response = await gapi.client.calendar.events.list({
                        'calendarId': CALENDAR_ID,
                        'timeMin': new Date().toISOString(),
                        'showDeleted': false,
                        'singleEvents': true,
                        'maxResults': 100,
                        'orderBy': 'startTime',
                    });
                    
                    console.log('Events fetched:', response);
                    const events = response.result.items;
                    
                    // Add events to the calendar
                    events.forEach(event => {
                        const start = event.start.dateTime || event.start.date;
                        const end = event.end.dateTime || event.end.date;
                        
                        calendar.addEvent({
                            id: event.id,
                            title: event.summary,
                            start: start,
                            end: end,
                            extendedProps: {
                                description: event.description || '',
                                location: event.location || '',
                                attendees: event.attendees || [],
                                organizer: event.organizer || {},
                                googleEventId: event.id
                            }
                        });
                    });
                    
                    console.log('Calendar auto-loaded successfully');
                    
                    // Start auto-sync if not already running
                    startAutoSync();
                } catch (error) {
                    console.error('Error auto-loading calendar:', error);
                }
            };
            
            // Request access token
            if (gapi.client.getToken() === null) {
                console.log('No token, requesting access');
                tokenClient.requestAccessToken({
                    prompt: 'consent',
                    redirect_uri: window.location.origin + window.location.pathname
                });
            } else {
                console.log('Token exists, refreshing');
                tokenClient.requestAccessToken({
                    prompt: '',
                    redirect_uri: window.location.origin + window.location.pathname
                });
            }
        }
        
        // Sync Google Calendar button click handler
        document.getElementById('syncGoogleCalendar').addEventListener('click', function() {
            console.log('Sync button clicked');
            if (gapiInited && gisInited) {
                console.log('APIs initialized, proceeding with auth');
                
                // Define the callback function
                tokenClient.callback = async (resp) => {
                    console.log('Auth callback received:', resp);
                    if (resp.error !== undefined) {
                        console.error('Auth error:', resp.error);
                        Swal.fire({
                            title: 'Authentication Error',
                            text: 'Failed to authenticate with Google Calendar: ' + resp.error,
                            icon: 'error'
                        });
                        return;
                    }
                    
                    try {
                        // Clear existing events
                        calendar.removeAllEvents();
                        console.log('Fetching events from Google Calendar');
                        
                        // Fetch events from Google Calendar
                        const response = await gapi.client.calendar.events.list({
                            'calendarId': CALENDAR_ID,
                            'timeMin': new Date().toISOString(),
                            'showDeleted': false,
                            'singleEvents': true,
                            'maxResults': 100,
                            'orderBy': 'startTime',
                        });
                        
                        console.log('Events fetched:', response);
                        const events = response.result.items;
                        
                        // Add events to the calendar
                        events.forEach(event => {
                            const start = event.start.dateTime || event.start.date;
                            const end = event.end.dateTime || event.end.date;
                            
                            calendar.addEvent({
                                id: event.id,
                                title: event.summary,
                                start: start,
                                end: end,
                                extendedProps: {
                                    description: event.description || '',
                                    location: event.location || '',
                                    attendees: event.attendees || [],
                                    organizer: event.organizer || {},
                                    googleEventId: event.id
                                }
                            });
                        });
                        
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Google Calendar events have been synced.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Start auto-sync if not already running
                        startAutoSync();
                    } catch (error) {
                        console.error('Error fetching events:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to fetch events from Google Calendar: ' + error.message,
                            icon: 'error'
                        });
                    }
                };
                
                // Request access token
                if (gapi.client.getToken() === null) {
                    console.log('No token, requesting access');
                    // Use a more specific approach for the token request
                    tokenClient.requestAccessToken({
                        prompt: 'consent',
                        redirect_uri: window.location.origin + window.location.pathname
                    });
                } else {
                    console.log('Token exists, refreshing');
                    tokenClient.requestAccessToken({
                        prompt: '',
                        redirect_uri: window.location.origin + window.location.pathname
                    });
                }
            } else {
                console.error('APIs not initialized:', { gapiInited, gisInited });
                Swal.fire({
                    title: 'Error!',
                    text: 'Google Calendar API is not properly initialized. Please refresh the page and try again.',
                    icon: 'error'
                });
            }
        });
        
        // Add Event button click handler
        document.getElementById('addEvent').addEventListener('click', function() {
            var addEventModal = new bootstrap.Modal(document.getElementById('addEventModal'));
            addEventModal.show();
        });
        
        // Save Event button click handler
        document.getElementById('saveEvent').addEventListener('click', function() {
            const title = document.getElementById('eventTitleInput').value;
            const startDate = document.getElementById('eventStartDate').value;
            const startTime = document.getElementById('eventStartTime').value;
            const endDate = document.getElementById('eventEndDate').value;
            const endTime = document.getElementById('eventEndTime').value;
            const location = document.getElementById('eventLocationInput').value;
            const description = document.getElementById('eventDescriptionInput').value;
            const attendeesInput = document.getElementById('eventAttendeesInput').value;
            
            if (!title || !startDate || !startTime || !endDate || !endTime) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fill in all required fields.',
                    icon: 'error'
                });
                return;
            }
            
            // Format dates for Google Calendar API
            const startDateTime = new Date(startDate + 'T' + startTime);
            const endDateTime = new Date(endDate + 'T' + endTime);
            
            // Create attendees array
            const attendees = attendeesInput.split(',').map(email => email.trim()).filter(email => email);
            
            // Create event object
            const event = {
                'summary': title,
                'location': location,
                'description': description,
                'start': {
                    'dateTime': startDateTime.toISOString(),
                    'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone
                },
                'end': {
                    'dateTime': endDateTime.toISOString(),
                    'timeZone': Intl.DateTimeFormat().resolvedOptions().timeZone
                },
                'attendees': attendees.map(email => ({'email': email}))
            };
            
            // If connected to Google Calendar, add event there
            if (gapiInited && gisInited && gapi.client.getToken() !== null) {
                gapi.client.calendar.events.insert({
                    'calendarId': 'crsmoving08@gmail.com',
                    'resource': event
                }).then(response => {
                    // Add event to local calendar
                    calendar.addEvent({
                        id: response.result.id,
                        title: title,
                        start: startDateTime,
                        end: endDateTime,
                        extendedProps: {
                            description: description,
                            location: location,
                            attendees: attendees.map(email => ({'email': email})),
                            googleEventId: response.result.id
                        }
                    });
                    
                    // Close modal and show success message
                    bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Event has been added to your calendar.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Reset form
                    document.getElementById('addEventForm').reset();
                }).catch(error => {
                    console.error('Error adding event to Google Calendar:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to add event to Google Calendar.',
                        icon: 'error'
                    });
                });
            } else {
                // Add event to local calendar only
                calendar.addEvent({
                    title: title,
                    start: startDateTime,
                    end: endDateTime,
                    extendedProps: {
                        description: description,
                        location: location,
                        attendees: attendees.map(email => ({'email': email}))
                    }
                });
                
                // Close modal and show success message
                bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                Swal.fire({
                    title: 'Success!',
                    text: 'Event has been added to your calendar.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Reset form
                document.getElementById('addEventForm').reset();
            }
        });
        
        // Function to open event details modal
        function openEventModal(event) {
            // Format date for display
            const startDate = new Date(event.start);
            const endDate = new Date(event.end);
            
            const day = startDate.getDate();
            const month = startDate.toLocaleString('default', { month: 'short' });
            
            const startTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const endTime = endDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Update modal content
            document.getElementById('eventDay').textContent = day;
            document.getElementById('eventMonth').textContent = month;
            document.getElementById('eventTitle').textContent = event.title;
            document.getElementById('eventTime').textContent = `${startTime} - ${endTime}`;
            document.getElementById('eventLocation').textContent = event.extendedProps.location || 'No location specified';
            document.getElementById('eventOrganizer').textContent = event.extendedProps.organizer?.displayName || 'Unknown';
            
            // Handle HTML content in description
            const descriptionElement = document.getElementById('eventDescription');
            if (event.extendedProps.description) {
                // Check if the description contains structured data
                if (event.extendedProps.description.includes('New moving service request details:')) {
                    // Format the structured content
                    formatStructuredDescription(event.extendedProps.description, descriptionElement);
                } else {
                    // Set innerHTML to render HTML content for non-structured descriptions
                    descriptionElement.innerHTML = event.extendedProps.description;
                    
                    // Add styling to links in description
                    const links = descriptionElement.querySelectorAll('a');
                    links.forEach(link => {
                        link.classList.add('text-primary');
                        link.style.textDecoration = 'none';
                        link.style.fontWeight = '500';
                        link.addEventListener('mouseover', function() {
                            this.style.textDecoration = 'underline';
                        });
                        link.addEventListener('mouseout', function() {
                            this.style.textDecoration = 'none';
                        });
                    });
                    
                    // Style paragraphs in description
                    const paragraphs = descriptionElement.querySelectorAll('p');
                    paragraphs.forEach(p => {
                        p.style.marginBottom = '0.5rem';
                    });
                }
            } else {
                descriptionElement.textContent = 'No description provided';
            }
            
            // Update Google Calendar link
            if (event.extendedProps.googleEventId) {
                document.getElementById('eventGoogleLink').href = `https://calendar.google.com/calendar/event?eid=${event.extendedProps.googleEventId}`;
                document.getElementById('eventGoogleLink').style.display = 'inline-block';
            } else {
                document.getElementById('eventGoogleLink').style.display = 'none';
            }
            
            // Clear and update attendees
            const attendeesContainer = document.getElementById('eventAttendees');
            attendeesContainer.innerHTML = '';
            
            if (event.extendedProps.attendees && event.extendedProps.attendees.length > 0) {
                event.extendedProps.attendees.forEach(attendee => {
                    const attendeeElement = document.createElement('div');
                    attendeeElement.className = 'attendee me-2 mb-2';
                    attendeeElement.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-center me-2">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <span>${attendee.email}</span>
                        </div>
                    `;
                    attendeesContainer.appendChild(attendeeElement);
                });
            } else {
                attendeesContainer.innerHTML = '<p class="text-muted">No attendees</p>';
            }
            
            // Show modal
            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        }
        
        // Function to format structured description
        function formatStructuredDescription(description, element) {
            // Create a container for the formatted content
            const container = document.createElement('div');
            container.className = 'structured-description';
            
            // Split the description into sections
            const sections = description.split('\n\n');
            
            sections.forEach(section => {
                if (section.trim() === '') return;
                
                // Check if this is a section header
                if (section.includes(':')) {
                    const lines = section.split('\n');
                    const header = lines[0].trim();
                    
                    // Create section header
                    const headerElement = document.createElement('h6');
                    headerElement.className = 'section-header';
                    headerElement.textContent = header;
                    container.appendChild(headerElement);
                    
                    // Create section content
                    const contentElement = document.createElement('div');
                    contentElement.className = 'section-content';
                    
                    // Process the rest of the lines
                    for (let i = 1; i < lines.length; i++) {
                        const line = lines[i].trim();
                        if (line === '') continue;
                        
                        // Check if this is a service item
                        if (line.startsWith('- Service Name:')) {
                            const serviceElement = document.createElement('div');
                            serviceElement.className = 'service-item';
                            
                            // Extract service name
                            const serviceName = line.split(':')[1].trim();
                            const serviceNameElement = document.createElement('div');
                            serviceNameElement.className = 'service-name';
                            serviceNameElement.textContent = serviceName;
                            serviceElement.appendChild(serviceNameElement);
                            
                            // Add service details
                            const detailsElement = document.createElement('div');
                            detailsElement.className = 'service-details';
                            
                            // Process service details
                            let j = i + 1;
                            while (j < lines.length && !lines[j].trim().startsWith('- Service Name:') && !lines[j].trim().startsWith('Cost Breakdown:')) {
                                const detailLine = lines[j].trim();
                                if (detailLine !== '') {
                                    const detailElement = document.createElement('div');
                                    detailElement.className = 'service-detail';
                                    detailElement.textContent = detailLine;
                                    detailsElement.appendChild(detailElement);
                                }
                                j++;
                            }
                            
                            serviceElement.appendChild(detailsElement);
                            contentElement.appendChild(serviceElement);
                            i = j - 1;
                        } 
                        // Check if this is a number of items line
                        else if (line.startsWith('Number of Items:')) {
                            const itemsElement = document.createElement('div');
                            itemsElement.className = 'items-count';
                            itemsElement.textContent = line;
                            contentElement.appendChild(itemsElement);
                        }
                        // Check if this is an image list
                        else if (line.startsWith('Uploaded Images:')) {
                            const imageUrls = line.split(':')[1].split(',').map(url => url.trim());
                            const imageContainer = document.createElement('div');
                            imageContainer.className = 'image-container';
                            
                            // Create a header for the image URLs
                            const imageHeader = document.createElement('div');
                            imageHeader.className = 'image-header';
                            imageHeader.textContent = 'Uploaded Images:';
                            imageContainer.appendChild(imageHeader);
                            
                            // Create a list of image URLs
                            const imageList = document.createElement('ul');
                            imageList.className = 'image-url-list';
                            
                            imageUrls.forEach(url => {
                                if (url) {
                                    const listItem = document.createElement('li');
                                    listItem.className = 'image-url-item';
                                    
                                    // Create a clickable link
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.textContent = url;
                                    link.target = '_blank';
                                    link.className = 'image-url-link';
                                    
                                    listItem.appendChild(link);
                                    imageList.appendChild(listItem);
                                }
                            });
                            
                            imageContainer.appendChild(imageList);
                            contentElement.appendChild(imageContainer);
                        }
                        // Regular line
                        else {
                            const lineElement = document.createElement('div');
                            lineElement.className = 'content-line';
                            lineElement.textContent = line;
                            contentElement.appendChild(lineElement);
                        }
                    }
                    
                    container.appendChild(contentElement);
                }
            });
            
            // Clear the element and append the formatted content
            element.innerHTML = '';
            element.appendChild(container);
        }
        
        // Function to start auto-sync
        function startAutoSync() {
            // Clear any existing interval
            if (autoSyncInterval) {
                clearInterval(autoSyncInterval);
            }
            
            // Set up auto-sync every 5 minutes
            autoSyncInterval = setInterval(async () => {
                console.log('Auto-syncing calendar...');
                try {
                    // Check if we have a valid token
                    if (gapi.client.getToken() === null) {
                        console.log('No token available for auto-sync, attempting to refresh');
                        // Try to refresh the token
                        tokenClient.callback = async (resp) => {
                            if (resp.error === undefined) {
                                await syncCalendar();
                            } else {
                                console.error('Auto-sync token refresh failed:', resp.error);
                            }
                        };
                        tokenClient.requestAccessToken({ prompt: '' });
                    } else {
                        // We have a valid token, proceed with sync
                        await syncCalendar();
                    }
                } catch (error) {
                    console.error('Auto-sync error:', error);
                }
            }, 5 * 60 * 1000); // 5 minutes
            
            console.log('Auto-sync started');
        }
        
        // Function to sync calendar
        async function syncCalendar() {
            try {
                // Clear existing events
                calendar.removeAllEvents();
                console.log('Fetching events from Google Calendar');
                
                // Fetch events from Google Calendar
                const response = await gapi.client.calendar.events.list({
                    'calendarId': CALENDAR_ID,
                    'timeMin': new Date().toISOString(),
                    'showDeleted': false,
                    'singleEvents': true,
                    'maxResults': 100,
                    'orderBy': 'startTime',
                });
                
                console.log('Events fetched:', response);
                const events = response.result.items;
                
                // Add events to the calendar
                events.forEach(event => {
                    const start = event.start.dateTime || event.start.date;
                    const end = event.end.dateTime || event.end.date;
                    
                    calendar.addEvent({
                        id: event.id,
                        title: event.summary,
                        start: start,
                        end: end,
                        extendedProps: {
                            description: event.description || '',
                            location: event.location || '',
                            attendees: event.attendees || [],
                            organizer: event.organizer || {},
                            googleEventId: event.id
                        }
                    });
                });
                
                console.log('Calendar synced successfully');
            } catch (error) {
                console.error('Error syncing calendar:', error);
            }
        }
        
        // Calendar navigation buttons
        document.getElementById('calendar-prev').addEventListener('click', function() {
            calendar.prev();
        });
        
        document.getElementById('calendar-next').addEventListener('click', function() {
            calendar.next();
        });
        
        document.getElementById('calendar-today').addEventListener('click', function() {
            calendar.today();
        });
        
        // Load Google API scripts
        function loadGoogleAPIs() {
            console.log('Loading Google APIs');
            try {
                const script1 = document.createElement('script');
                script1.src = 'https://apis.google.com/js/api.js';
                script1.onload = gapiLoaded;
                script1.onerror = function() {
                    console.error('Failed to load Google API script');
                };
                document.body.appendChild(script1);
                
                const script2 = document.createElement('script');
                script2.src = 'https://accounts.google.com/gsi/client';
                script2.onload = gisLoaded;
                script2.onerror = function() {
                    console.error('Failed to load Google Identity Services script');
                };
                document.body.appendChild(script2);
            } catch (error) {
                console.error('Error loading Google APIs:', error);
            }
        }
        
        // Initialize Google APIs
        loadGoogleAPIs();
        
        // Start auto-sync when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM content loaded, checking API initialization status');
            
            // If APIs are already initialized, attempt to load calendar data
            if (gapiInited && gisInited) {
                console.log('APIs already initialized, attempting to load calendar data');
                attemptAutoLoad();
            } else {
                // If APIs aren't initialized yet, wait for them
                console.log('Waiting for APIs to initialize');
                const checkInterval = setInterval(() => {
                    if (gapiInited && gisInited) {
                        clearInterval(checkInterval);
                        console.log('APIs initialized, attempting to load calendar data');
                        attemptAutoLoad();
                    }
                }, 500);
            }
        });
    });
</script>

<style>
    /* Modern Calendar Styling */
    .fc {
        font-family: 'Poppins', sans-serif;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        padding: 15px;
    }
    
    .fc-toolbar-title {
        font-weight: 600;
        color: #333;
        font-size: 1.5rem !important;
    }
    
    .fc-button-primary {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
        box-shadow: 0 2px 5px rgba(78, 115, 223, 0.2);
        transition: all 0.3s ease;
    }
    
    .fc-button-primary:hover {
        background-color: #2e59d9 !important;
        border-color: #2e59d9 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .fc-button-primary:not(:disabled).fc-button-active, 
    .fc-button-primary:not(:disabled):active {
        background-color: #2e59d9 !important;
        border-color: #2e59d9 !important;
    }
    
    .fc-daygrid-day {
        transition: background-color 0.3s ease;
    }
    
    .fc-daygrid-day:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    
    .fc-day-today {
        background-color: rgba(78, 115, 223, 0.1) !important;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background-color: #4e73df;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px;
    }
    
    .fc-event {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 6px;
        border: none;
        padding: 4px 8px;
        margin: 2px 0;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .fc-event-modern {
        background-color: #4e73df;
        border-left: 4px solid #2e59d9;
    }
    
    .fc-event-time {
        font-weight: 600;
    }
    
    .fc-event-title {
        font-weight: 500;
    }
    
    .fc-daygrid-event {
        white-space: normal;
        align-items: center;
    }
    
    .fc-daygrid-event-dot {
        display: none;
    }
    
    .fc-daygrid-event-time {
        padding-left: 0;
    }
    
    .fc-daygrid-event-title {
        padding-left: 0;
    }
    
    .fc-daygrid-day-events {
        margin-top: 5px;
    }
    
    .fc-daygrid-day-number {
        font-weight: 500;
        color: #555;
    }
    
    .fc-col-header-cell {
        padding: 10px 0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #555;
    }
    
    .fc-theme-standard td, 
    .fc-theme-standard th {
        border-color: #e3e6f0;
    }
    
    /* Modal Styling */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #e3e6f0;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 1.5rem;
    }
    
    .event-date {
        background: linear-gradient(135deg, #4e73df, #2e59d9);
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }
    
    .event-date h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .event-date p {
        font-size: 1.2rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    .attendee {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(78, 115, 223, 0.1);
        color: #4e73df;
        font-size: 0.9rem;
    }
    
    /* Form Styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 0.5rem;
    }
    
    /* Button Styling */
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        box-shadow: 0 2px 5px rgba(78, 115, 223, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .btn-outline-primary {
        color: #4e73df;
        border-color: #4e73df;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
        box-shadow: 0 2px 5px rgba(133, 135, 150, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background-color: #717384;
        border-color: #717384;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(133, 135, 150, 0.3);
    }
    
    /* Card Styling */
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.25rem 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .card-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }
    
    /* Page Title Styling */
    .page-title-box {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .fc-toolbar-chunk {
            margin-bottom: 10px;
        }
        
        .event-date {
            margin-bottom: 20px;
        }
    }
    
    /* Description Styling */
    #eventDescription {
        line-height: 1.6;
        color: #555;
    }
    
    #eventDescription p {
        margin-bottom: 0.5rem;
    }
    
    #eventDescription a {
        color: #4e73df;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    #eventDescription a:hover {
        text-decoration: underline;
    }
    
    #eventDescription u {
        text-decoration: none;
        border-bottom: 1px solid #4e73df;
    }
    
    /* Structured Description Styling */
    .structured-description {
        font-family: 'Poppins', sans-serif;
    }
    
    .section-header {
        font-weight: 600;
        color: #333;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .section-content {
        margin-bottom: 1rem;
    }
    
    .content-line {
        margin-bottom: 0.5rem;
    }
    
    .service-item {
        background-color: #f8f9fc;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #4e73df;
    }
    
    .service-name {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 0.5rem;
    }
    
    .service-details {
        padding-left: 1rem;
    }
    
    .service-detail {
        margin-bottom: 0.25rem;
        color: #555;
    }
    
    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 1rem;
    }
    
    .uploaded-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .uploaded-image:hover {
        transform: scale(1.05);
    }
    
    /* Image Error Styling */
    .image-error {
        opacity: 0.7;
        border: 1px dashed #ccc;
    }
    
    /* Image URL List Styling */
    .image-header {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #4e73df;
    }
    
    .image-url-list {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    
    .image-url-item {
        margin-bottom: 0.5rem;
        word-break: break-all;
    }
    
    .image-url-link {
        color: #4e73df;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .image-url-link:hover {
        text-decoration: underline;
        color: #2e59d9;
    }
    
    /* Items Count Styling */
    .items-count {
        font-weight: 500;
        margin-bottom: 1rem;
        color: #4e73df;
        background-color: #f8f9fc;
        padding: 0.5rem;
        border-radius: 4px;
        border-left: 3px solid #4e73df;
    }
</style>