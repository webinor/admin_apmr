
  var protocol = location.protocol === 'https:' ? "https://" : "http://"  ;
  var host = location.host;// document.domain ;

  var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
      var matches, substringRegex;

      // an array that will be populated with substring matches
      matches = [];

      // regex used to determine if a string contains the substring `q`
      var substrRegex = new RegExp(q, 'i');

      // iterate through the pool of strings and for any string that
      // contains the substring `q`, add it to the `matches` array
      for (var i = 0; i < strs.length; i++) {
        if (substrRegex.test(strs[i])) {
          matches.push(strs[i]);
        }
      }

      cb(matches);
    };
  };

  var resources =[], states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
    'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
    'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
    'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
    'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
    'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
    'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
    'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
    'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
  ];

function attach_typeahead(input) {
  

 // console.log(input);
  

  // constructs the suggestion engine
  var prestations = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    // `states` is an array of state names defined in "The Basics"
   // local: states
   prefetch: { 
    url: $("#extractor_typeahead_url").val() ? $("#extractor_typeahead_url").val() :  '/api/getResources',
    cache:false,
    prepare: function (query , settings = {}) {
     // console.log(settings);
      var provider = $("#form-search #provider").val();
      settings['url'] =  query.url ;// +( '&query=' + related_identifier );
      settings['data'] =  {provider:provider};
      settings['headers'] = {
        "Authorization": `Bearer ${window.localStorage.getItem('token')}`
      };

    //  console.log(settings);

  
      return settings;
    },
    filter:function(response){

      //console.log(response);
     
      return response ;

    }
  },
   // prefetch: location.protocol +'/api/getResources'
  });
//console.log(resources);

input.typeahead({
  hint: true,
  highlight: true,
  minLength: 1
}, {
  name: 'resources',
  source: prestations ,
  limit:10
});


}

