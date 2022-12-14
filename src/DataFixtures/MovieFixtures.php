<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    private const JSON = <<<EOD
[
    {"Title":"Star Wars: Episode IV - A New Hope","Year":"1977","Rated":"PG","Released":"25 May 1977","Runtime":"121 min","Genre":"Action, Adventure, Fantasy","Director":"George Lucas","Writer":"George Lucas","Actors":"Mark Hamill, Harrison Ford, Carrie Fisher","Plot":"Luke Skywalker joins forces with a Jedi Knight, a cocky pilot, a Wookiee and two droids to save the galaxy from the Empire's world-destroying battle station, while also attempting to rescue Princess Leia from the mysterious Darth ...","Language":"English","Country":"United States","Awards":"Won 6 Oscars. 64 wins & 29 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"8.6/10"},{"Source":"Rotten Tomatoes","Value":"93%"},{"Source":"Metacritic","Value":"90/100"}],"Metascore":"90","imdbRating":"8.6","imdbVotes":"1,359,272","imdbID":"tt0076759","Type":"movie","DVD":"06 Dec 2005","BoxOffice":"$460,998,507","Production":"N/A","Website":"N/A","Response":"True"},
    {"Title":"Star Wars: Episode V - The Empire Strikes Back","Year":"1980","Rated":"PG","Released":"20 Jun 1980","Runtime":"124 min","Genre":"Action, Adventure, Fantasy","Director":"Irvin Kershner","Writer":"Leigh Brackett, Lawrence Kasdan, George Lucas","Actors":"Mark Hamill, Harrison Ford, Carrie Fisher","Plot":"After the Rebels are brutally overpowered by the Empire on the ice planet Hoth, Luke Skywalker begins Jedi training with Yoda, while his friends are pursued across the galaxy by Darth Vader and bounty hunter Boba Fett.","Language":"English","Country":"United States","Awards":"Won 1 Oscar. 26 wins & 20 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BYmU1NDRjNDgtMzhiMi00NjZmLTg5NGItZDNiZjU5NTU4OTE0XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"8.7/10"},{"Source":"Rotten Tomatoes","Value":"94%"},{"Source":"Metacritic","Value":"82/100"}],"Metascore":"82","imdbRating":"8.7","imdbVotes":"1,287,175","imdbID":"tt0080684","Type":"movie","DVD":"21 Sep 2004","BoxOffice":"$292,753,960","Production":"N/A","Website":"N/A","Response":"True"},
    {"Title":"Star Wars: Episode VI - Return of the Jedi","Year":"1983","Rated":"PG","Released":"25 May 1983","Runtime":"131 min","Genre":"Action, Adventure, Fantasy","Director":"Richard Marquand","Writer":"Lawrence Kasdan, George Lucas","Actors":"Mark Hamill, Harrison Ford, Carrie Fisher","Plot":"After a daring mission to rescue Han Solo from Jabba the Hutt, the Rebels dispatch to Endor to destroy the second Death Star. Meanwhile, Luke struggles to help Darth Vader back from the dark side without falling into the Emperor's...","Language":"English, Kikuyu, Tagalog, Kalmyk-Oirat, Quechua, Polish","Country":"United States","Awards":"Nominated for 4 Oscars. 23 wins & 20 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BOWZlMjFiYzgtMTUzNC00Y2IzLTk1NTMtZmNhMTczNTk0ODk1XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"8.3/10"},{"Source":"Rotten Tomatoes","Value":"83%"},{"Source":"Metacritic","Value":"58/100"}],"Metascore":"58","imdbRating":"8.3","imdbVotes":"1,050,753","imdbID":"tt0086190","Type":"movie","DVD":"21 Sep 2004","BoxOffice":"$309,306,177","Production":"N/A","Website":"N/A","Response":"True"},
    {"Title":"Star Wars: Episode I - The Phantom Menace","Year":"1999","Rated":"PG","Released":"19 May 1999","Runtime":"136 min","Genre":"Action, Adventure, Fantasy","Director":"George Lucas","Writer":"George Lucas","Actors":"Ewan McGregor, Liam Neeson, Natalie Portman","Plot":"Two Jedi escape a hostile blockade to find allies and come across a young boy who may bring balance to the Force, but the long dormant Sith resurface to claim their original glory.","Language":"English, Sanskrit","Country":"United States","Awards":"Nominated for 3 Oscars. 26 wins & 69 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BYTRhNjcwNWQtMGJmMi00NmQyLWE2YzItODVmMTdjNWI0ZDA2XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"6.5/10"},{"Source":"Rotten Tomatoes","Value":"51%"},{"Source":"Metacritic","Value":"51/100"}],"Metascore":"51","imdbRating":"6.5","imdbVotes":"804,744","imdbID":"tt0120915","Type":"movie","DVD":"22 Mar 2005","BoxOffice":"$474,544,677","Production":"N/A","Website":"N/A","Response":"True"},
    {"Title":"Star Wars: Episode II - Attack of the Clones","Year":"2002","Rated":"PG","Released":"16 May 2002","Runtime":"142 min","Genre":"Action, Adventure, Fantasy","Director":"George Lucas","Writer":"George Lucas, Jonathan Hales, John Ostrander","Actors":"Hayden Christensen, Natalie Portman, Ewan McGregor","Plot":"Ten years after initially meeting, Anakin Skywalker shares a forbidden romance with Padmé Amidala, while Obi-Wan Kenobi investigates an assassination attempt on the senator and discovers a secret clone army crafted for the Jedi.","Language":"English","Country":"United States","Awards":"Nominated for 1 Oscar. 19 wins & 65 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BMDAzM2M0Y2UtZjRmZi00MzVlLTg4MjEtOTE3NzU5ZDVlMTU5XkEyXkFqcGdeQXVyNDUyOTg3Njg@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"6.6/10"},{"Source":"Rotten Tomatoes","Value":"65%"},{"Source":"Metacritic","Value":"54/100"}],"Metascore":"54","imdbRating":"6.6","imdbVotes":"710,634","imdbID":"tt0121765","Type":"movie","DVD":"22 Mar 2005","BoxOffice":"$310,676,740","Production":"N/A","Website":"N/A","Response":"True"},
    {"Title":"Star Wars: Episode III - Revenge of the Sith","Year":"2005","Rated":"PG-13","Released":"19 May 2005","Runtime":"140 min","Genre":"Action, Adventure, Fantasy","Director":"George Lucas","Writer":"George Lucas, John Ostrander, Jan Duursema","Actors":"Hayden Christensen, Natalie Portman, Ewan McGregor","Plot":"Three years into the Clone Wars, the Jedi rescue Palpatine from Count Dooku. As Obi-Wan pursues a new threat, Anakin acts as a double agent between the Jedi Council and Palpatine and is lured into a sinister plan to rule the galaxy.","Language":"English","Country":"United States, Italy, Switzerland, Thailand","Awards":"Nominated for 1 Oscar. 26 wins & 63 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BNTc4MTc3NTQ5OF5BMl5BanBnXkFtZTcwOTg0NjI4NA@@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"7.6/10"},{"Source":"Rotten Tomatoes","Value":"79%"},{"Source":"Metacritic","Value":"68/100"}],"Metascore":"68","imdbRating":"7.6","imdbVotes":"790,637","imdbID":"tt0121766","Type":"movie","DVD":"01 Nov 2005","BoxOffice":"$380,270,577","Production":"N/A","Website":"N/A","Response":"True"}
]
EOD;

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getJsonDecoded() as $item) {
            $date = $item['Released'] === 'N/A' ? $item['Year'] : $item['Released'];
            $movie = (new Movie())
                ->setTitle($item['Title'])
                ->setPoster($item['Poster'])
                ->setCountry($item['Country'])
                ->setReleasedAt(new \DateTimeImmutable($date))
                ->setPlot($item['Plot'])
                ->setRated($item['Rated'])
                ->setImdbId($item['imdbID'])
                ->setPrice(500)
                ;
            $genres = explode(', ', $item['Genre']);
            foreach ($genres as $genre) {
                $movie->addGenre((new Genre())->setName($genre)->setPoster($item['Poster']));
            }
            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getJsonDecoded(): \Generator
    {
        $decoded = \json_decode(self::JSON, true);

        foreach ($decoded as $item) {
           yield $item;
        }
    }
}
