<?php

declare(strict_types=1);

namespace Doctrine\Website\Repositories;

use Doctrine\SkeletonMapper\ObjectRepository\BasicObjectRepository;
use Doctrine\Website\Model\TeamMember;

class TeamMemberRepository extends BasicObjectRepository
{
    public function findOneByGithub(string $github) : ?TeamMember
    {
        return $this->findOneBy(['github' => $github]);
    }

    /**
     * @return TeamMember[]
     */
    public function findConsultants() : array
    {
        return $this->findBy(['consultant' => true], ['github' => 'asc']);
    }
}
