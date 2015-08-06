<?php

namespace Oro\BugBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\BugBundle\Model\ExtendIssue;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation\Loggable;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * Issue
 * @ORM\Table(name="oro_bug_issue")
 * @ORM\Entity(repositoryClass="Oro\BugBundle\Entity\IssueRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Loggable
 * @Config(
 *  defaultValues={
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          }
 * }
 * )
 */
class Issue extends ExtendIssue
{
    const TYPE_BUG = 'bug';
    const TYPE_SUBTASK = 'subtask';
    const TYPE_TASK = 'task';
    const TYPE_STORY = 'story';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=1000)
     * @ORM\Column(name="summary", type="string", length=1000)
     */
    private $summary;

    /**
     * @var string
     * @Assert\Length(min=5)
     * @Assert\Length(max=5)
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=10000)
     * @ORM\Column(name="description", type="string", length=10000)
     */
    private $description;

    /**
     * @var IssuePriority
     * @ORM\ManyToOne(targetEntity="Oro\BugBundle\Entity\IssuePriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id", nullable=false)
     */
    private $priority;

    /**
     * @var IssueStatus
     * @ORM\ManyToOne(targetEntity="Oro\BugBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     */
    private $status;

    /**
     * @var IssueResolution
     * @ORM\ManyToOne(targetEntity="Oro\BugBundle\Entity\IssueResolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id", nullable=false)
     */
    private $resolution;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", nullable=false)
     */
    private $assignee;

    /**
     * @var Collection | User[]
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User", inversedBy="issues")
     * @ORM\JoinTable(name="oro_bug_collaborators")
     **/
    private $collaborators;

    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="Oro\BugBundle\Entity\Issue", inversedBy="childrenIssues")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $parentIssue;

    /**
     * @var Collection | Issue[]
     * @ORM\OneToMany(targetEntity="Oro\BugBundle\Entity\Issue", mappedBy="parentIssue")
     */
    private $childrenIssues;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    public function __construct()
    {
        $this->childrenIssues = new ArrayCollection();
        $this->collaborators = new ArrayCollection();

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getCode().' '.(string)$this->getSummary();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }


    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedNow()
    {
        $this->created = new \DateTime('now', new \DateTimeZone('UTC'));

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCodeStamp()
    {
        $this->code = substr(md5(microtime()), rand(0, 26), 5);

        return $this;
    }


    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     */
    public function setUpdatedNow()
    {
        $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set priority
     *
     * @param IssuePriority $priority
     * @return Issue
     */
    public function setPriority(IssuePriority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param IssueStatus $status
     * @return Issue
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set resolution
     *
     * @param IssueResolution $resolution
     * @return Issue
     */
    public function setResolution(IssueResolution $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set owner
     *
     * @param User $reporter
     * @return Issue
     */
    public function setOwner(User $reporter)
    {
        $this->owner = $reporter;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set assignee
     *
     * @param User $assignee
     * @return Issue
     */
    public function setAssignee(User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add collaborators
     *
     * @param User $collaborators
     * @return Issue
     */
    public function addCollaborator(User $collaborators)
    {
        foreach ($this->collaborators as $col) {
            if ($col == $collaborators) {
                return $this;
            }
        }

        $this->collaborators[] = $collaborators;

        return $this;
    }

    /**
     * Remove collaborators
     *
     * @param User $collaborators
     */
    public function removeCollaborator(User $collaborators)
    {
        $this->collaborators->removeElement($collaborators);
    }

    /**
     * Get collaborators
     *
     * @return Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Set parentIssue
     *
     * @param Issue $parentIssue
     * @return Issue
     */
    public function setParentIssue(Issue $parentIssue)
    {
        $this->parentIssue = $parentIssue;

        return $this;
    }

    /**
     * Get parentIssue
     *
     * @return Issue
     */
    public function getParentIssue()
    {
        return $this->parentIssue;
    }

    /**
     * Add childrenIssues
     *
     * @param Issue $childrenIssues
     * @return Issue
     */
    public function addChildrenIssue(Issue $childrenIssues)
    {
        $this->childrenIssues[] = $childrenIssues;

        return $this;
    }

    /**
     * Remove childrenIssues
     *
     * @param Issue $childrenIssues
     */
    public function removeChildrenIssue(Issue $childrenIssues)
    {
        $this->childrenIssues->removeElement($childrenIssues);
    }

    /**
     * Get childrenIssues
     *
     * @return Collection
     */
    public function getChildrenIssues()
    {
        return $this->childrenIssues;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     * @return Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
