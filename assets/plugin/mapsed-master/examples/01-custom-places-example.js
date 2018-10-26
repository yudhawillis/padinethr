function runExample3() {
    $("#custom-places").mapsed({
		showOnLoad: 	
		[
			// City Varieties
			{
				// flag that this place should have the tooltip shown when the map is first loaded
				//autoShow: false,
				// flags the user can edit this place
				canEdit: false,
				lat: -7.2931227,
				lng:112.7190426,
				//reference: "CoQBfAAAAPw-5BTCS53grSLDwX8rwo5BnWnEWnA72lmOjxdgWg2ODGfC5lLjGyoz428IEaln1vJ6rq1jI96Npzlm-N-wmPH2jdJMGfOLxno_rmgnajAnMPzNzuI8UjexIOdHVZPBPvQGloC-tRhudGeKkbdTT-IWNP5hp4DIl4XOLWuYFOVYEhBxNPxaXZdW9uhKIETXf60hGhTc9yKchnS6oO-6z5XZJkK2ekewYQ"
                name:"Kantor Padinet",
                street: "Jl. May.Jend Sungkono, No.83 <br> <a href='dev.jamuiboe.com'>http://padi.net.id</a>"
			},
            {
                // flag that this place should have the tooltip shown when the map is first loaded
                //autoShow: false,
                // flags the user can edit this place
                canEdit: false,
                lat: -7.2845528,
                lng:112.7175738,
                //reference: "CoQBfAAAAPw-5BTCS53grSLDwX8rwo5BnWnEWnA72lmOjxdgWg2ODGfC5lLjGyoz428IEaln1vJ6rq1jI96Npzlm-N-wmPH2jdJMGfOLxno_rmgnajAnMPzNzuI8UjexIOdHVZPBPvQGloC-tRhudGeKkbdTT-IWNP5hp4DIl4XOLWuYFOVYEhBxNPxaXZdW9uhKIETXf60hGhTc9yKchnS6oO-6z5XZJkK2ekewYQ"
                name: "Kosan Willis",
                street: "Jl. DKT 14 / 38 <br> <a href='dev.jamuiboe.com'>http://yudhawillys.tumblr.com</a>"
            }//,
			// Random made up CUSTOM place
			//{
				// flag that this place should have the tooltip shown when the map is first loaded
				//autoShow: true,
				//lat: 53.79,
				//lng:-1.5426760000000286,
				//name: "Somewhere",
				//street: "Over the rainbow, Up high way",
				//userData: 99
			//}

		]
		
	});									
}


$(document).ready(function() {
	runExample3();
});


