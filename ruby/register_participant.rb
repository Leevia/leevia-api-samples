require 'net/http'
require 'openssl'
require 'json'
require 'rest-client'

CAMPAIGN_ID = 999 # TODO: replace 999 with your campaign id
API_KEY = 'xxx' # TODO: replace xxx with your api key
API_SECRET = 'yyy' # TODO: replace yyy with your api secret
END_POINT = 'https://app.leevia.com/api/v1/campaigns'

### Get authorization JWT token ###
uri = URI(
  [END_POINT, CAMPAIGN_ID, 'authenticate'].join('/')
)
request = Net::HTTP::Get.new(uri)
request['Accept'] = 'application/vnd.leevia.api.v1+json'
request['Content-Type'] = 'application/json'
request['App-Key'] = API_KEY
timestamp = Time.now
request['Timestamp'] = timestamp
request['Signature'] = OpenSSL::HMAC.hexdigest('sha256', API_SECRET, "#{API_KEY}.#{timestamp}")
result = Net::HTTP.start(uri.hostname, uri.port, use_ssl: uri.scheme == "https") do |http|
  http.request(request)
end
auth = result['Authorization']
puts "Auth code: #{auth}"

### Register participant ###
participant = {
  first_name: 'Mario',
  last_name: 'Rossi',
  email: 'mario.rossi@example.com',
  registration_ip: '192.168.0.4',
  custom_data: {
    date_of_birth: '1989-10-03'
  },
  # uncomment the line below and replace YOUR_PATH with the file you want to upload
  # file_field: File.new('YOUR_PATH'),
  acceptances: {
    rules: true,
    newsletter: false
  }
}

RestClient.log = 'stdout'

begin
  response = RestClient.post(
    [END_POINT, 'instant_wins', CAMPAIGN_ID, 'participants'].join('/'),
    participant,
    {
      "Accept": 'application/vnd.leevia.api.v1+json',
      "Authorization": "#{auth}"
    }
  )

  puts response.code
  puts response.body
rescue RestClient::UnprocessableEntity => e
  puts e.response
end
