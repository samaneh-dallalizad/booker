# Assignment

With the given pseudo code, which is about the BookingService and implements a basic create and cancel methods to handle a Booking object,
implement 2 calls on the TrivagoHandler; one to send the create request and one to send the cancellation request.

Guidelines:
- Trivago API docs: https://developer.trivago.com/conversiontracking/conversion-api.html
- use the Guzzle PHP HTTP client -or- use file_get_contents() and stream_context_create()
- assume all mandatory Trivago properties (like trv_reference and advertiser_id) are available on the $Booking object
- only send bookings to Trivago when the $Booking->referral_provider property equals 'Trivago', return bool:false if not
- if the Trivago response state is OK, return bool:true from the handler
- the property keyValidDaysRemaining may be ignored
- if the Trivago response state is FAILED, log the message via the warning() method and return bool:false from the handler
- no need for actual working code, pseudo code is enough
- tests are not mandatory, but if you got some ideas on how to test your code, please supply them
- please document your process and steps along the way
