

## Quick install instructions

- Install composer (https://getcomposer.org/)
- run 'composer install' on root directory
- do php artisan key:generate
- point your web browser the the root_directory/public

If permissions issue arise, set your apache user (www-data) to own the root_directory/storage and root_directory/bootstrap

## The game

- You have 3 guesses to get the right number. 
- The number will only ever be 1 to 10.
- The game will give you feedback about your guesses, ranging from cold (3+ away from the correct number) to hot (1 away from the correct number)
- Good Luck, Have Fun.


## Techinal specs

95% of the code is in either
- app/Http/Controllers/NumberGameController.php
- resources/views/numberGame.blade.php

Other than these two I have some routes in the 
- routes/web.php


## Thoughts

Laravel may have been overkill for such a simple game, but I expect that we'll expand on this. So I wanted a full toolkit to be ready for anything.
