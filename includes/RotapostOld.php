<?php

//namespace Rotapost;

final class Client {

    const API_URL = 'https://api.rotapost.ru';

    private $apiKey;

    public function __construct($apiKey = NULL) {
        $this->apiKey = $apiKey;
    }

    public function campaignIndex() {
        $parameters = array();

        return $this->executeRequest(
                        'GET', '/Campaign/Index', $parameters, array());
    }

    public function campaignArchive($campaignId) {
        $parameters = array(
            'campaignId' => $campaignId
        );

        return $this->executeRequest(
                        'POST', '/Campaign/Archive', array(), $parameters);
    }

    public function campaignUnarchive($campaignId) {
        $parameters = array(
            'campaignId' => $campaignId
        );

        return $this->executeRequest(
                        'POST', '/Campaign/Unarchive', array(), $parameters);
    }

    public function campaignOffers($campaignId) {
        $parameters = array(
            'campaignId' => $campaignId
        );

        return $this->executeRequest(
                        'GET', '/Campaign/Offers', $parameters, array());
    }

    public function campaignCreate($campaignTitle, $campaignBudget = NULL) {
        $parameters = array(
            'campaignTitle' => $campaignTitle
        );
        if ($campaignBudget != NULL) {
            $parameters['campaignBudget'] = $campaignBudget;
        }
        return $this->executeRequest(
                        'POST', '/Campaign/Create', array(), $parameters);
    }

    public function campaignEdit($campaignId, $campaignTitle = NULL, $campaignBudget = NULL) {
        $parameters = array(
            'campaignId' => $campaignId
        );
        if ($campaignTitle != NULL) {
            $parameters['campaignTitle'] = $campaignTitle;
        }
        if ($campaignBudget != NULL) {
            $parameters['campaignBudget'] = $campaignBudget;
        }
        return $this->executeRequest(
                        'POST', '/Campaign/Edit', array(), $parameters);
    }

    public function campaignShow($campaignId) {
        $parameters = array(
            'campaignId' => $campaignId
        );

        return $this->executeRequest(
                        'GET', '/Campaign/Show', $parameters, array());
    }

    public function offerShow($offerId) {
        $parameters = array(
            'offerId' => $offerId
        );

        return $this->executeRequest(
                        'GET', '/Offer/Show', $parameters, array());
    }

    public function offerDelete($offerId) {
        $parameters = array(
            'offerId' => $offerId
        );

        return $this->executeRequest(
                        'POST', '/Offer/Delete', array(), $parameters);
    }

    public function offerCreatePostovoi($title, $url, $anchor, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
        $parameters = array(
            'title' => $title,
            'url' => $url,
            'anchor' => $anchor,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($strFreeBlogIds != NULL) {
            $parameters['strFreeBlogIds'] = $strFreeBlogIds;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreatePostovoi', array(), $parameters);
    }

    public function offerEditPostovoi($categoriesIds, $offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $title = NULL, $url = NULL, $anchor = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $languageIds = NULL, $budget = NULL) {
        $parameters = array(
            'categoriesIds' => $categoriesIds,
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($title != NULL) {
            $parameters['title'] = $title;
        }
        if ($url != NULL) {
            $parameters['url'] = $url;
        }
        if ($anchor != NULL) {
            $parameters['anchor'] = $anchor;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($strFreeBlogIds != NULL) {
            $parameters['strFreeBlogIds'] = $strFreeBlogIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditPostovoi', array(), $parameters);
    }

    public function offerCreatePressRelease($title, $description, $criterionUrl, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $onlyTwoLevel = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $budget = NULL) {
        $parameters = array(
            'title' => $title,
            'description' => $description,
            'criterionUrl' => $criterionUrl,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($criterionAnchor != NULL) {
            $parameters['criterionAnchor'] = $criterionAnchor;
        }
        if ($criterionText != NULL) {
            $parameters['criterionText'] = $criterionText;
        }
        if ($textVariants != NULL) {
            $parameters['textVariants'] = $textVariants;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($strFreeBlogIds != NULL) {
            $parameters['strFreeBlogIds'] = $strFreeBlogIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreatePressRelease', array(), $parameters);
    }

    public function offerEditPressRelease($criterionUrl, $offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $title = NULL, $description = NULL, $comment = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $strFreeBlogIds = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
        $parameters = array(
            'criterionUrl' => $criterionUrl,
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($title != NULL) {
            $parameters['title'] = $title;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($criterionAnchor != NULL) {
            $parameters['criterionAnchor'] = $criterionAnchor;
        }
        if ($criterionText != NULL) {
            $parameters['criterionText'] = $criterionText;
        }
        if ($textVariants != NULL) {
            $parameters['textVariants'] = $textVariants;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($strFreeBlogIds != NULL) {
            $parameters['strFreeBlogIds'] = $strFreeBlogIds;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditPressRelease', array(), $parameters);
    }

    public function offerCreatePost($title, $description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $criterionUrl = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $criterionPostLength = NULL, $onlyTwoLevel = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $freeBlogIds = NULL, $budget = NULL) {
        $parameters = array(
            'title' => $title,
            'description' => $description,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($criterionUrl != NULL) {
            $parameters['criterionUrl'] = $criterionUrl;
        }
        if ($criterionAnchor != NULL) {
            $parameters['criterionAnchor'] = $criterionAnchor;
        }
        if ($criterionText != NULL) {
            $parameters['criterionText'] = $criterionText;
        }
        if ($textVariants != NULL) {
            $parameters['textVariants'] = $textVariants;
        }
        if ($criterionPostLength != NULL) {
            $parameters['criterionPostLength'] = $criterionPostLength;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($freeBlogIds != NULL) {
            $parameters['freeBlogIds'] = $freeBlogIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreatePost', array(), $parameters);
    }

    public function offerEditPost($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $title = NULL, $description = NULL, $criterionUrl = NULL, $criterionAnchor = NULL, $criterionText = NULL, $textVariants = NULL, $criterionPostLength = NULL, $onlyTwoLevel = NULL, $minPrice = NULL, $maxPrice = NULL, $minPr = NULL, $maxPr = NULL, $minCY = NULL, $maxCY = NULL, $minAlexa = NULL, $maxAlexa = NULL, $minPagesInGoogle = NULL, $maxPagesInGoogle = NULL, $minPagesInYandex = NULL, $maxPagesInYandex = NULL, $minCompleteRank = NULL, $maxCompleteRank = NULL, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $freeBlogIds = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($title != NULL) {
            $parameters['title'] = $title;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($criterionUrl != NULL) {
            $parameters['criterionUrl'] = $criterionUrl;
        }
        if ($criterionAnchor != NULL) {
            $parameters['criterionAnchor'] = $criterionAnchor;
        }
        if ($criterionText != NULL) {
            $parameters['criterionText'] = $criterionText;
        }
        if ($textVariants != NULL) {
            $parameters['textVariants'] = $textVariants;
        }
        if ($criterionPostLength != NULL) {
            $parameters['criterionPostLength'] = $criterionPostLength;
        }
        if ($onlyTwoLevel != NULL) {
            $parameters['onlyTwoLevel'] = $onlyTwoLevel;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minPr != NULL) {
            $parameters['minPr'] = $minPr;
        }
        if ($maxPr != NULL) {
            $parameters['maxPr'] = $maxPr;
        }
        if ($minCY != NULL) {
            $parameters['minCY'] = $minCY;
        }
        if ($maxCY != NULL) {
            $parameters['maxCY'] = $maxCY;
        }
        if ($minAlexa != NULL) {
            $parameters['minAlexa'] = $minAlexa;
        }
        if ($maxAlexa != NULL) {
            $parameters['maxAlexa'] = $maxAlexa;
        }
        if ($minPagesInGoogle != NULL) {
            $parameters['minPagesInGoogle'] = $minPagesInGoogle;
        }
        if ($maxPagesInGoogle != NULL) {
            $parameters['maxPagesInGoogle'] = $maxPagesInGoogle;
        }
        if ($minPagesInYandex != NULL) {
            $parameters['minPagesInYandex'] = $minPagesInYandex;
        }
        if ($maxPagesInYandex != NULL) {
            $parameters['maxPagesInYandex'] = $maxPagesInYandex;
        }
        if ($minCompleteRank != NULL) {
            $parameters['minCompleteRank'] = $minCompleteRank;
        }
        if ($maxCompleteRank != NULL) {
            $parameters['maxCompleteRank'] = $maxCompleteRank;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($freeBlogIds != NULL) {
            $parameters['freeBlogIds'] = $freeBlogIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditPost', array(), $parameters);
    }

    public function offerCreateTweetWithMention($description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'description' => $description,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateTweetWithMention', array(), $parameters);
    }

    public function offerEditTweetWithMention($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $description = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditTweetWithMention', array(), $parameters);
    }

    public function offerCreateReTweetRt($description, $title, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $minStatuses = NULL, $maxStatuses = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'description' => $description,
            'title' => $title,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($minStatuses != NULL) {
            $parameters['minStatuses'] = $minStatuses;
        }
        if ($maxStatuses != NULL) {
            $parameters['maxStatuses'] = $maxStatuses;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateReTweetRt', array(), $parameters);
    }

    public function offerEditReTweetRt($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $minStatuses = NULL, $maxStatuses = NULL, $description = NULL, $title = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($minStatuses != NULL) {
            $parameters['minStatuses'] = $minStatuses;
        }
        if ($maxStatuses != NULL) {
            $parameters['maxStatuses'] = $maxStatuses;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($title != NULL) {
            $parameters['title'] = $title;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditReTweetRt', array(), $parameters);
    }

    public function offerCreateTweet($url, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'url' => $url,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateTweet', array(), $parameters);
    }

    public function offerEditTweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $url = NULL, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($url != NULL) {
            $parameters['url'] = $url;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditTweet', array(), $parameters);
    }

    public function offerCreateTweetWithNickname($userName, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'userName' => $userName,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateTweetWithNickname', array(), $parameters);
    }

    public function offerEditTweetWithNickname($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $userName = NULL, $description = NULL, $comment = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($userName != NULL) {
            $parameters['userName'] = $userName;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($comment != NULL) {
            $parameters['comment'] = $comment;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditTweetWithNickname', array(), $parameters);
    }

    public function offerCreateViaRetweet($userName, $description, $campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'userName' => $userName,
            'description' => $description,
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateViaRetweet', array(), $parameters);
    }

    public function offerEditViaRetweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $userName = NULL, $description = NULL, $categoriesIds = NULL, $languageIds = NULL, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minRatio = NULL, $maxRatio = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($userName != NULL) {
            $parameters['userName'] = $userName;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minRatio != NULL) {
            $parameters['minRatio'] = $minRatio;
        }
        if ($maxRatio != NULL) {
            $parameters['maxRatio'] = $maxRatio;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditViaRetweet', array(), $parameters);
    }

    public function offerCreateRetweet($campaignId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $minPrice = NULL, $maxPrice = NULL, $minFollowers = NULL, $maxFollowers = NULL, $minFriends = NULL, $maxFriends = NULL, $minTweets = NULL, $maxTweets = NULL, $minListed = NULL, $maxListed = NULL, $minKlout = NULL, $maxKlout = NULL, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
        $parameters = array(
            'campaignId' => $campaignId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($minPrice != NULL) {
            $parameters['minPrice'] = $minPrice;
        }
        if ($maxPrice != NULL) {
            $parameters['maxPrice'] = $maxPrice;
        }
        if ($minFollowers != NULL) {
            $parameters['minFollowers'] = $minFollowers;
        }
        if ($maxFollowers != NULL) {
            $parameters['maxFollowers'] = $maxFollowers;
        }
        if ($minFriends != NULL) {
            $parameters['minFriends'] = $minFriends;
        }
        if ($maxFriends != NULL) {
            $parameters['maxFriends'] = $maxFriends;
        }
        if ($minTweets != NULL) {
            $parameters['minTweets'] = $minTweets;
        }
        if ($maxTweets != NULL) {
            $parameters['maxTweets'] = $maxTweets;
        }
        if ($minListed != NULL) {
            $parameters['minListed'] = $minListed;
        }
        if ($maxListed != NULL) {
            $parameters['maxListed'] = $maxListed;
        }
        if ($minKlout != NULL) {
            $parameters['minKlout'] = $minKlout;
        }
        if ($maxKlout != NULL) {
            $parameters['maxKlout'] = $maxKlout;
        }
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/CreateRetweet', array(), $parameters);
    }

    public function offerEditRetweet($offerId, $userActive, $onlyTenderEnabled, $acceptBlogRules, $chanceFinishedTask = NULL, $categoriesIds = NULL, $languageIds = NULL, $budget = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'userActive' => $userActive,
            'onlyTenderEnabled' => $onlyTenderEnabled,
            'acceptBlogRules' => $acceptBlogRules
        );
        if ($chanceFinishedTask != NULL) {
            $parameters['chanceFinishedTask'] = $chanceFinishedTask;
        }
        if ($categoriesIds != NULL) {
            $parameters['categoriesIds'] = $categoriesIds;
        }
        if ($languageIds != NULL) {
            $parameters['languageIds'] = $languageIds;
        }
        if ($budget != NULL) {
            $parameters['budget'] = $budget;
        }
        return $this->executeRequest(
                        'POST', '/Offer/EditRetweet', array(), $parameters);
    }

    public function listAddSite($siteUrl, $listType) {
        $parameters = array(
            'siteUrl' => $siteUrl,
            'listType' => $listType
        );

        return $this->executeRequest(
                        'POST', '/List/AddSite', array(), $parameters);
    }

    public function listSites($listType) {
        $parameters = array(
            'listType' => $listType
        );

        return $this->executeRequest(
                        'GET', '/List/Sites', $parameters, array());
    }

    public function listRemoveSite($siteUrl, $listType) {
        $parameters = array(
            'siteUrl' => $siteUrl,
            'listType' => $listType
        );

        return $this->executeRequest(
                        'POST', '/List/RemoveSite', array(), $parameters);
    }

    public function messageIndex($taskId) {
        $parameters = array(
            'taskId' => $taskId
        );

        return $this->executeRequest(
                        'GET', '/Message/Index', $parameters, array());
    }

    public function messageSend($taskId, $message) {
        $parameters = array(
            'taskId' => $taskId,
            'message' => $message
        );

        return $this->executeRequest(
                        'POST', '/Message/Send', array(), $parameters);
    }

    public function reportWebmasterEarnings($fromDate, $toDate, $siteId = NULL) {
        $parameters = array(
            'fromDate' => $fromDate,
            'toDate' => $toDate
        );
        if ($siteId != NULL) {
            $parameters['siteId'] = $siteId;
        }
        return $this->executeRequest(
                        'GET', '/Report/WebmasterEarnings', $parameters, array());
    }

    public function reportWebmasterTask($fromDate, $toDate, $siteId = NULL) {
        $parameters = array(
            'fromDate' => $fromDate,
            'toDate' => $toDate
        );
        if ($siteId != NULL) {
            $parameters['siteId'] = $siteId;
        }
        return $this->executeRequest(
                        'GET', '/Report/WebmasterTask', $parameters, array());
    }

    public function reportAdvertiserByCampaignId($campaignId, $startDate, $endDate, $dateType = NULL) {
        $parameters = array(
            'campaignId' => $campaignId,
            'startDate' => $startDate,
            'endDate' => $endDate
        );
        if ($dateType != NULL) {
            $parameters['dateType'] = $dateType;
        }
        return $this->executeRequest(
                        'GET', '/Report/AdvertiserByCampaignId', $parameters, array());
    }

    public function reportAdvertiserByOfferId($offerId, $startDate, $endDate, $dateType = NULL) {
        $parameters = array(
            'offerId' => $offerId,
            'startDate' => $startDate,
            'endDate' => $endDate
        );
        if ($dateType != NULL) {
            $parameters['dateType'] = $dateType;
        }
        return $this->executeRequest(
                        'GET', '/Report/AdvertiserByOfferId', $parameters, array());
    }

    public function taskAdvertiser($status, $campaignId = NULL, $offerId = NULL, $onlyTasksWithErrors = NULL) {
        $parameters = array(
            'status' => $status
        );
        if ($campaignId != NULL) {
            $parameters['campaignId'] = $campaignId;
        }
        if ($offerId != NULL) {
            $parameters['offerId'] = $offerId;
        }
        if ($onlyTasksWithErrors != NULL) {
            $parameters['onlyTasksWithErrors'] = $onlyTasksWithErrors;
        }
        return $this->executeRequest(
                        'GET', '/Task/Advertiser', $parameters, array());
    }

    public function taskWebmaster($status, $siteId = NULL, $onlyTasksWithErrors = NULL) {
        $parameters = array(
            'status' => $status
        );
        if ($siteId != NULL) {
            $parameters['siteId'] = $siteId;
        }
        if ($onlyTasksWithErrors != NULL) {
            $parameters['onlyTasksWithErrors'] = $onlyTasksWithErrors;
        }
        return $this->executeRequest(
                        'GET', '/Task/Webmaster', $parameters, array());
    }

    public function taskTake($taskId) {
        $parameters = array(
            'taskId' => $taskId
        );

        return $this->executeRequest(
                        'POST', '/Task/Take', array(), $parameters);
    }

    public function taskComplete($taskId, $checkUrl) {
        $parameters = array(
            'taskId' => $taskId,
            'checkUrl' => $checkUrl
        );

        return $this->executeRequest(
                        'POST', '/Task/Complete', array(), $parameters);
    }

    public function taskApprove($taskId) {
        $parameters = array(
            'taskId' => $taskId
        );

        return $this->executeRequest(
                        'POST', '/Task/Approve', array(), $parameters);
    }

    public function taskReject($taskId, $reason = NULL) {
        $parameters = array(
            'taskId' => $taskId
        );
        if ($reason != NULL) {
            $parameters['reason'] = $reason;
        }
        return $this->executeRequest(
                        'POST', '/Task/Reject', array(), $parameters);
    }

    public function taskActions($taskId) {
        $parameters = array(
            'taskId' => $taskId
        );

        return $this->executeRequest(
                        'GET', '/Task/Actions', $parameters, array());
    }

    public function taskShow($taskId) {
        $parameters = array(
            'taskId' => $taskId
        );

        return $this->executeRequest(
                        'GET', '/Task/Show', $parameters, array());
    }

    public function taskCreate($offerId, $siteUrl) {
        $parameters = array(
            'offerId' => $offerId,
            'siteUrl' => $siteUrl
        );

        return $this->executeRequest(
                        'GET', '/Task/Create', $parameters, array());
    }

    public function massTaskOrder($siteId = NULL) {
        $parameters = array(
        );
        if ($siteId != NULL) {
            $parameters['siteId'] = $siteId;
        }
        return $this->executeRequest(
                        'POST', '/MassTask/Order', array(), $parameters);
    }

    public function loginTemp() {
        $parameters = array(
        );

        return $this->executeRequest(
                        'GET', '/Login/Temp', $parameters, array());
    }

    public function loginAuth($login, $authToken) {
        $parameters = array(
            'login' => $login,
            'authToken' => $authToken
        );

        $result = $this->executeRequest(
                'GET', '/Login/Auth', $parameters, array());
        $this->apiKey = $result->ApiKey;
        return $result;
    }

    public function dashboardAdvertiser($campaignId = NULL) {
        $parameters = array();
        if ($campaignId != NULL) {
            $parameters['campaignId'] = $campaignId;
        }
        return $this->executeRequest(
                        'GET', '/Dashboard/Advertiser', $parameters, array());
    }

    public function dashboardWebmaster($siteId = NULL) {
        $parameters = array();
        if ($siteId != NULL) {
            $parameters['siteId'] = $siteId;
        }
        return $this->executeRequest(
                        'GET', '/Dashboard/Webmaster', $parameters, array());
    }

    public function siteIndex() {
        $parameters = array();

        return $this->executeRequest(
                        'GET', '/Site/Index', $parameters, array());
    }

    public function siteShow($siteId) {
        $parameters = array(
            'siteId' => $siteId
        );

        return $this->executeRequest(
                        'GET', '/Site/Show', $parameters, array());
    }

    public function siteEdit($siteId, $isSold = NULL, $description = NULL, $postPrice = NULL, $pressReleasePrice = NULL, $postovoiPrice = NULL) {
        $parameters = array(
            'siteId' => $siteId
        );
        if ($isSold != NULL) {
            $parameters['isSold'] = $isSold;
        }
        if ($description != NULL) {
            $parameters['description'] = $description;
        }
        if ($postPrice != NULL) {
            $parameters['postPrice'] = $postPrice;
        }
        if ($pressReleasePrice != NULL) {
            $parameters['pressReleasePrice'] = $pressReleasePrice;
        }
        if ($postovoiPrice != NULL) {
            $parameters['postovoiPrice'] = $postovoiPrice;
        }
        return $this->executeRequest(
                        'GET', '/Site/Edit', $parameters, array());
    }

    public function balanceIndex() {
        $parameters = array();

        return $this->executeRequest(
                        'GET', '/Balance/Index', $parameters, array());
    }

    public function buyFilters() {
        $parameters = array();

        return $this->executeRequest(
                        'GET', '/Buy/Filters', $parameters, array());
    }

    public function buySites($filter = NULL) {
        $parameters = array();
        if ($filter != NULL) {
            $parameters['filter'] = $filter;
        }
        return $this->executeRequest(
                        'GET', '/Buy/Sites', $parameters, array());
    }

    public function buyTwitters() {
        $parameters = array();

        return $this->executeRequest(
                        'GET', '/Buy/Twitters', $parameters, array());
    }

    private function executeRequest($method, $path, $query, $body) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $url = self::API_URL . $path . '?apiKey=' . $this->apiKey;
        if (count($query) > 0) {
            $url = $url . '&' . http_build_query($query);
        }

        //echo $url . "<br>";
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
