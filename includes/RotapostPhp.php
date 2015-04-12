<?php
    namespace Rotapost;

    final class Client {
        const API_URL = 'https://api.rotapost.ru';
        private $apiKey;

        public function __construct($apiKey = NULL) {
            $this->apiKey = $apiKey;
        }

        public function campaignIndex() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Campaign/Index',
                $parameters,
                []);
        }

        public function campaignArchive($campaignId) {
            $parameters = 
            [
                'campaignId' => $campaignId
            ];
            
            return $this->executeRequest(
                'POST',
                '/Campaign/Archive',
                [],
                $parameters);
        }

        public function campaignUnarchive($campaignId) {
            $parameters = 
            [
                'campaignId' => $campaignId
            ];
            
            return $this->executeRequest(
                'POST',
                '/Campaign/Unarchive',
                [],
                $parameters);
        }

        public function campaignOffers($campaignId) {
            $parameters = 
            [
                'campaignId' => $campaignId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Campaign/Offers',
                $parameters,
                []);
        }

        public function campaignCreate($campaignTitle, $campaignBudget = NULL) {
            $parameters = 
            [
                'campaignTitle' => $campaignTitle
            ];
            if ($campaignBudget != NULL) { $parameters['campaignBudget'] = $campaignBudget; }
            return $this->executeRequest(
                'POST',
                '/Campaign/Create',
                [],
                $parameters);
        }

        public function campaignEdit($campaignId, $campaignTitle = NULL, $campaignBudget = NULL) {
            $parameters = 
            [
                'campaignId' => $campaignId
            ];
            if ($campaignTitle != NULL) { $parameters['campaignTitle'] = $campaignTitle; }
            if ($campaignBudget != NULL) { $parameters['campaignBudget'] = $campaignBudget; }
            return $this->executeRequest(
                'POST',
                '/Campaign/Edit',
                [],
                $parameters);
        }

        public function campaignShow($campaignId) {
            $parameters = 
            [
                'campaignId' => $campaignId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Campaign/Show',
                $parameters,
                []);
        }

        public function offerShow($offerId) {
            $parameters = 
            [
                'offerId' => $offerId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Offer/Show',
                $parameters,
                []);
        }

        public function offerDelete($offerId) {
            $parameters = 
            [
                'offerId' => $offerId
            ];
            
            return $this->executeRequest(
                'POST',
                '/Offer/Delete',
                [],
                $parameters);
        }

        public function offerCreatePostovoi($title, $url, $anchor, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'title' => $title,
                'url' => $url,
                'anchor' => $anchor,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($strFreeBlogIds != NULL) { $parameters['strFreeBlogIds'] = $strFreeBlogIds; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreatePostovoi',
                [],
                $parameters);
        }

        public function offerEditPostovoi($categoriesIds, $offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $title = NULL, $url = NULL, $anchor = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $languageIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'categoriesIds' => $categoriesIds,
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($title != NULL) { $parameters['title'] = $title; }
            if ($url != NULL) { $parameters['url'] = $url; }
            if ($anchor != NULL) { $parameters['anchor'] = $anchor; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($strFreeBlogIds != NULL) { $parameters['strFreeBlogIds'] = $strFreeBlogIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditPostovoi',
                [],
                $parameters);
        }

        public function offerCreatePressRelease($title, $description, $criterionUrl, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $onlyTwoLevel = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'title' => $title,
                'description' => $description,
                'criterionUrl' => $criterionUrl,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($criterionAnchor != NULL) { $parameters['criterionAnchor'] = $criterionAnchor; }
            if ($criterionText != NULL) { $parameters['criterionText'] = $criterionText; }
            if ($textVariants != NULL) { $parameters['textVariants'] = $textVariants; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($strFreeBlogIds != NULL) { $parameters['strFreeBlogIds'] = $strFreeBlogIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreatePressRelease',
                [],
                $parameters);
        }

        public function offerEditPressRelease($criterionUrl, $offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $title = NULL, $description = NULL, $comment = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'criterionUrl' => $criterionUrl,
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($title != NULL) { $parameters['title'] = $title; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($criterionAnchor != NULL) { $parameters['criterionAnchor'] = $criterionAnchor; }
            if ($criterionText != NULL) { $parameters['criterionText'] = $criterionText; }
            if ($textVariants != NULL) { $parameters['textVariants'] = $textVariants; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($strFreeBlogIds != NULL) { $parameters['strFreeBlogIds'] = $strFreeBlogIds; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditPressRelease',
                [],
                $parameters);
        }

        public function offerCreatePost($title, $description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $criterionUrl = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $criterionPostLength = NULL, $onlyTwoLevel = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $freeBlogIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'title' => $title,
                'description' => $description,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($criterionUrl != NULL) { $parameters['criterionUrl'] = $criterionUrl; }
            if ($criterionAnchor != NULL) { $parameters['criterionAnchor'] = $criterionAnchor; }
            if ($criterionText != NULL) { $parameters['criterionText'] = $criterionText; }
            if ($textVariants != NULL) { $parameters['textVariants'] = $textVariants; }
            if ($criterionPostLength != NULL) { $parameters['criterionPostLength'] = $criterionPostLength; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($freeBlogIds != NULL) { $parameters['freeBlogIds'] = $freeBlogIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreatePost',
                [],
                $parameters);
        }

        public function offerEditPost($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $title = NULL, $description = NULL, $criterionUrl = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $criterionPostLength = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $freeBlogIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($title != NULL) { $parameters['title'] = $title; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($criterionUrl != NULL) { $parameters['criterionUrl'] = $criterionUrl; }
            if ($criterionAnchor != NULL) { $parameters['criterionAnchor'] = $criterionAnchor; }
            if ($criterionText != NULL) { $parameters['criterionText'] = $criterionText; }
            if ($textVariants != NULL) { $parameters['textVariants'] = $textVariants; }
            if ($criterionPostLength != NULL) { $parameters['criterionPostLength'] = $criterionPostLength; }
            if ($onlyTwoLevel != NULL) { $parameters['onlyTwoLevel'] = $onlyTwoLevel; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minPr != NULL) { $parameters['minPr'] = $minPr; }
            if ($maxPr != NULL) { $parameters['maxPr'] = $maxPr; }
            if ($minCY != NULL) { $parameters['minCY'] = $minCY; }
            if ($maxCY != NULL) { $parameters['maxCY'] = $maxCY; }
            if ($minAlexa != NULL) { $parameters['minAlexa'] = $minAlexa; }
            if ($maxAlexa != NULL) { $parameters['maxAlexa'] = $maxAlexa; }
            if ($minPagesInGoogle != NULL) { $parameters['minPagesInGoogle'] = $minPagesInGoogle; }
            if ($maxPagesInGoogle != NULL) { $parameters['maxPagesInGoogle'] = $maxPagesInGoogle; }
            if ($minPagesInYandex != NULL) { $parameters['minPagesInYandex'] = $minPagesInYandex; }
            if ($maxPagesInYandex != NULL) { $parameters['maxPagesInYandex'] = $maxPagesInYandex; }
            if ($minCompleteRank != NULL) { $parameters['minCompleteRank'] = $minCompleteRank; }
            if ($maxCompleteRank != NULL) { $parameters['maxCompleteRank'] = $maxCompleteRank; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($freeBlogIds != NULL) { $parameters['freeBlogIds'] = $freeBlogIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditPost',
                [],
                $parameters);
        }

        public function offerCreateTweetWithMention($description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'description' => $description,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateTweetWithMention',
                [],
                $parameters);
        }

        public function offerEditTweetWithMention($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $description = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditTweetWithMention',
                [],
                $parameters);
        }

        public function offerCreateReTweetRt($description, $title, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $minStatuses = NULL, $maxStatuses = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'description' => $description,
                'title' => $title,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($minStatuses != NULL) { $parameters['minStatuses'] = $minStatuses; }
            if ($maxStatuses != NULL) { $parameters['maxStatuses'] = $maxStatuses; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateReTweetRt',
                [],
                $parameters);
        }

        public function offerEditReTweetRt($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $minStatuses = NULL, $maxStatuses = NULL, $description = NULL, $title = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($minStatuses != NULL) { $parameters['minStatuses'] = $minStatuses; }
            if ($maxStatuses != NULL) { $parameters['maxStatuses'] = $maxStatuses; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($title != NULL) { $parameters['title'] = $title; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditReTweetRt',
                [],
                $parameters);
        }

        public function offerCreateTweet($url, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'url' => $url,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateTweet',
                [],
                $parameters);
        }

        public function offerEditTweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $url = NULL, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($url != NULL) { $parameters['url'] = $url; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditTweet',
                [],
                $parameters);
        }

        public function offerCreateTweetWithNickname($userName, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'userName' => $userName,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateTweetWithNickname',
                [],
                $parameters);
        }

        public function offerEditTweetWithNickname($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $userName = NULL, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($userName != NULL) { $parameters['userName'] = $userName; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($comment != NULL) { $parameters['comment'] = $comment; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditTweetWithNickname',
                [],
                $parameters);
        }

        public function offerCreateViaRetweet($userName, $description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'userName' => $userName,
                'description' => $description,
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateViaRetweet',
                [],
                $parameters);
        }

        public function offerEditViaRetweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $userName = NULL, $description = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($userName != NULL) { $parameters['userName'] = $userName; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minRatio != NULL) { $parameters['minRatio'] = $minRatio; }
            if ($maxRatio != NULL) { $parameters['maxRatio'] = $maxRatio; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditViaRetweet',
                [],
                $parameters);
        }

        public function offerCreateRetweet($campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'campaignId' => $campaignId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($minPrice != NULL) { $parameters['minPrice'] = $minPrice; }
            if ($maxPrice != NULL) { $parameters['maxPrice'] = $maxPrice; }
            if ($minFollowers != NULL) { $parameters['minFollowers'] = $minFollowers; }
            if ($maxFollowers != NULL) { $parameters['maxFollowers'] = $maxFollowers; }
            if ($minFriends != NULL) { $parameters['minFriends'] = $minFriends; }
            if ($maxFriends != NULL) { $parameters['maxFriends'] = $maxFriends; }
            if ($minTweets != NULL) { $parameters['minTweets'] = $minTweets; }
            if ($maxTweets != NULL) { $parameters['maxTweets'] = $maxTweets; }
            if ($minListed != NULL) { $parameters['minListed'] = $minListed; }
            if ($maxListed != NULL) { $parameters['maxListed'] = $maxListed; }
            if ($minKlout != NULL) { $parameters['minKlout'] = $minKlout; }
            if ($maxKlout != NULL) { $parameters['maxKlout'] = $maxKlout; }
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/CreateRetweet',
                [],
                $parameters);
        }

        public function offerEditRetweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'userActive' => $userActive,
                'onlyTenderEnabled' => $onlyTenderEnabled,
                'acceptBlogRules' => $acceptBlogRules
            ];
            if ($chanceFinishedTask != NULL) { $parameters['chanceFinishedTask'] = $chanceFinishedTask; }
            if ($categoriesIds != NULL) { $parameters['categoriesIds'] = $categoriesIds; }
            if ($languageIds != NULL) { $parameters['languageIds'] = $languageIds; }
            if ($budget != NULL) { $parameters['budget'] = $budget; }
            return $this->executeRequest(
                'POST',
                '/Offer/EditRetweet',
                [],
                $parameters);
        }

        public function listAddSite($siteUrl, $listType) {
            $parameters = 
            [
                'siteUrl' => $siteUrl,
                'listType' => $listType
            ];
            
            return $this->executeRequest(
                'POST',
                '/List/AddSite',
                [],
                $parameters);
        }

        public function listSites($listType) {
            $parameters = 
            [
                'listType' => $listType
            ];
            
            return $this->executeRequest(
                'GET',
                '/List/Sites',
                $parameters,
                []);
        }

        public function listRemoveSite($siteUrl, $listType) {
            $parameters = 
            [
                'siteUrl' => $siteUrl,
                'listType' => $listType
            ];
            
            return $this->executeRequest(
                'POST',
                '/List/RemoveSite',
                [],
                $parameters);
        }

        public function messageIndex($taskId) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Message/Index',
                $parameters,
                []);
        }

        public function messageSend($taskId, $message) {
            $parameters = 
            [
                'taskId' => $taskId,
                'message' => $message
            ];
            
            return $this->executeRequest(
                'POST',
                '/Message/Send',
                [],
                $parameters);
        }

        public function reportWebmasterEarnings($fromDate, $toDate, $siteId = NULL) {
            $parameters = 
            [
                'fromDate' => $fromDate,
                'toDate' => $toDate
            ];
            if ($siteId != NULL) { $parameters['siteId'] = $siteId; }
            return $this->executeRequest(
                'GET',
                '/Report/WebmasterEarnings',
                $parameters,
                []);
        }

        public function reportWebmasterTask($fromDate, $toDate, $siteId = NULL) {
            $parameters = 
            [
                'fromDate' => $fromDate,
                'toDate' => $toDate
            ];
            if ($siteId != NULL) { $parameters['siteId'] = $siteId; }
            return $this->executeRequest(
                'GET',
                '/Report/WebmasterTask',
                $parameters,
                []);
        }

        public function reportAdvertiserByCampaignId($campaignId, $startDate, $endDate, $dateType = NULL) {
            $parameters = 
            [
                'campaignId' => $campaignId,
                'startDate' => $startDate,
                'endDate' => $endDate
            ];
            if ($dateType != NULL) { $parameters['dateType'] = $dateType; }
            return $this->executeRequest(
                'GET',
                '/Report/AdvertiserByCampaignId',
                $parameters,
                []);
        }

        public function reportAdvertiserByOfferId($offerId, $startDate, $endDate, $dateType = NULL) {
            $parameters = 
            [
                'offerId' => $offerId,
                'startDate' => $startDate,
                'endDate' => $endDate
            ];
            if ($dateType != NULL) { $parameters['dateType'] = $dateType; }
            return $this->executeRequest(
                'GET',
                '/Report/AdvertiserByOfferId',
                $parameters,
                []);
        }

        public function taskAdvertiser($status, $campaignId = NULL, $offerId = NULL, $onlyTasksWithErrors = NULL) {
            $parameters = 
            [
                'status' => $status
            ];
            if ($campaignId != NULL) { $parameters['campaignId'] = $campaignId; }
            if ($offerId != NULL) { $parameters['offerId'] = $offerId; }
            if ($onlyTasksWithErrors != NULL) { $parameters['onlyTasksWithErrors'] = $onlyTasksWithErrors; }
            return $this->executeRequest(
                'GET',
                '/Task/Advertiser',
                $parameters,
                []);
        }

        public function taskWebmaster($status, $siteId = NULL, $onlyTasksWithErrors = NULL) {
            $parameters = 
            [
                'status' => $status
            ];
            if ($siteId != NULL) { $parameters['siteId'] = $siteId; }
            if ($onlyTasksWithErrors != NULL) { $parameters['onlyTasksWithErrors'] = $onlyTasksWithErrors; }
            return $this->executeRequest(
                'GET',
                '/Task/Webmaster',
                $parameters,
                []);
        }

        public function taskTake($taskId) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            
            return $this->executeRequest(
                'POST',
                '/Task/Take',
                [],
                $parameters);
        }

        public function taskComplete($taskId, $checkUrl) {
            $parameters = 
            [
                'taskId' => $taskId,
                'checkUrl' => $checkUrl
            ];
            
            return $this->executeRequest(
                'POST',
                '/Task/Complete',
                [],
                $parameters);
        }

        public function taskApprove($taskId) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            
            return $this->executeRequest(
                'POST',
                '/Task/Approve',
                [],
                $parameters);
        }

        public function taskReject($taskId, $reason = NULL) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            if ($reason != NULL) { $parameters['reason'] = $reason; }
            return $this->executeRequest(
                'POST',
                '/Task/Reject',
                [],
                $parameters);
        }

        public function taskActions($taskId) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Task/Actions',
                $parameters,
                []);
        }

        public function taskShow($taskId) {
            $parameters = 
            [
                'taskId' => $taskId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Task/Show',
                $parameters,
                []);
        }

        public function taskCreate($offerId, $siteUrl) {
            $parameters = 
            [
                'offerId' => $offerId,
                'siteUrl' => $siteUrl
            ];
            
            return $this->executeRequest(
                'GET',
                '/Task/Create',
                $parameters,
                []);
        }

        public function loginTemp() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Login/Temp',
                $parameters,
                []);
        }

        public function loginAuth($login, $authToken) {
            $parameters = 
            [
                'login' => $login,
                'authToken' => $authToken
            ];
            
            $result = $this->executeRequest(
                'GET',
                '/Login/Auth',
                $parameters,
                []);
            $this->apiKey = $result->ApiKey;
            return $result;
        }

        public function dashboardAdvertiser($campaignId = NULL) {
            $parameters = 
            [
                
            ];
            if ($campaignId != NULL) { $parameters['campaignId'] = $campaignId; }
            return $this->executeRequest(
                'GET',
                '/Dashboard/Advertiser',
                $parameters,
                []);
        }

        public function dashboardWebmaster($siteId = NULL) {
            $parameters = 
            [
                
            ];
            if ($siteId != NULL) { $parameters['siteId'] = $siteId; }
            return $this->executeRequest(
                'GET',
                '/Dashboard/Webmaster',
                $parameters,
                []);
        }

        public function siteIndex() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Site/Index',
                $parameters,
                []);
        }

        public function siteShow($siteId) {
            $parameters = 
            [
                'siteId' => $siteId
            ];
            
            return $this->executeRequest(
                'GET',
                '/Site/Show',
                $parameters,
                []);
        }

        public function siteEdit($siteId, $isSold = NULL, $description = NULL, $postPrice = NULL, $pressReleasePrice = NULL, $postovoiPrice = NULL) {
            $parameters = 
            [
                'siteId' => $siteId
            ];
            if ($isSold != NULL) { $parameters['isSold'] = $isSold; }
            if ($description != NULL) { $parameters['description'] = $description; }
            if ($postPrice != NULL) { $parameters['postPrice'] = $postPrice; }
            if ($pressReleasePrice != NULL) { $parameters['pressReleasePrice'] = $pressReleasePrice; }
            if ($postovoiPrice != NULL) { $parameters['postovoiPrice'] = $postovoiPrice; }
            return $this->executeRequest(
                'GET',
                '/Site/Edit',
                $parameters,
                []);
        }

        public function balanceIndex() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Balance/Index',
                $parameters,
                []);
        }

        public function buyFilters() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Buy/Filters',
                $parameters,
                []);
        }

        public function buySites($filter = NULL) {
            $parameters = 
            [
                
            ];
            if ($filter != NULL) { $parameters['filter'] = $filter; }
            return $this->executeRequest(
                'GET',
                '/Buy/Sites',
                $parameters,
                []);
        }

        public function buyTwitters() {
            $parameters = 
            [
                
            ];
            
            return $this->executeRequest(
                'GET',
                '/Buy/Twitters',
                $parameters,
                []);
        }

        private function executeRequest($method, $path, $query, $body) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $url = self::API_URL . $path . '?apiKey=' . $this->apiKey;
            if (count($query) > 0) {
                $url = $url . '&' . http_build_query($query);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'RotapostClient/1.0.0.0 (PHP)');
            if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
            }
            $response = curl_exec($ch);
            if (!$response) {
                die(curl_error($ch));
            }
            curl_close($ch);
            return simplexml_load_string($response);
        }
    }
?>
