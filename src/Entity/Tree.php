<?php

namespace App\Entity;

use App\Manager\TreeStatusManager;
use App\Repository\TreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TreeRepository::class)
 * @ORM\EntityListeners({"App\EventListener\TreeListener"})
 */
class Tree extends QrCode
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrCode;

    /**
     * @ORM\ManyToOne(targetEntity=Species::class)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $species;

    /**
     * @ORM\ManyToOne(targetEntity=ColumnRow::class)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageRecovery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $output = false;

    /**
     * @ORM\ManyToOne(targetEntity=OutputType::class)
     */
    private $outputType;

    /**
     * @ORM\ManyToMany(targetEntity=Style::class)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $potentialStyles;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=TreeGrowth::class, mappedBy="tree", orphanRemoval=true)
     */
    private $treeGrowths;

    /**
     * @ORM\OneToMany(targetEntity=TreePicture::class, mappedBy="tree", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $treePictures;

    /**
     * @ORM\OneToMany(targetEntity=HistoryTree::class, mappedBy="tree", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"date" = "DESC", "id" = "DESC"})
     */
    private $historyTrees;

    /**
     * @ORM\ManyToOne(targetEntity=Lot::class, inversedBy="trees")
     */
    private $lot;

    /**
     * @ORM\ManyToOne(targetEntity=Nursery::class, inversedBy="trees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nursery;

    /**
     * @ORM\OneToMany(targetEntity=TreeWork::class, mappedBy="tree", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $works;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nebariDiameter;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $trunkDiameter;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = TreeStatusManager::NOT_ADOPTABLE;

    /**
     * @ORM\OneToOne(targetEntity=Adoption::class, mappedBy="tree", cascade={"persist", "remove"})
     */
    private $adoption;

    /**
     * @ORM\OneToOne(targetEntity=DeadTree::class, mappedBy="tree", cascade={"persist", "remove"})
     */
    private $deadTree;

    /**
     * @ORM\ManyToOne(targetEntity=PotType::class)
     */
    private $potType;

    /**
     * @ORM\ManyToOne(targetEntity=Greenhouse::class, inversedBy="trees")
     */
    private $greenhouse;

    /**
     * @ORM\ManyToOne(targetEntity=CultureTable::class)
     */
    private $cultureTable;

    /**
     * @ORM\ManyToOne(targetEntity=Segment::class)
     */
    private $segment;

    /**
     * @ORM\ManyToOne(targetEntity=TableColumn::class)
     */
    private $tableColumn;

    /**
     * @ORM\ManyToOne(targetEntity=ColumnRow::class)
     */
    private $columnRow;

    /**
     * @ORM\OneToMany(targetEntity=Observation::class, mappedBy="tree", orphanRemoval=true)
     */
    private $observations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $workingYear;

    public function __construct()
    {
        $this->treeGrowths = new ArrayCollection();
        $this->treePictures = new ArrayCollection();
        $this->historyTrees = new ArrayCollection();
        $this->works = new ArrayCollection();
        $this->potentialStyles = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->observations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getAge(): ?int
    {
        if ($this->getAgeRecovery())
        {
            if ($this->getLot() && ($dateRecovery = $this->getLot()->getEntryDate()))
            {
                return $this->getAgeRecovery() + date_diff($dateRecovery, new \DateTime())->y;
            }

            return $this->getAgeRecovery();
        }

        if (($lot = $this->getLot()) && $lot->getAgeRecovery() && ($dateRecovery = $lot->getEntryDate()))
        {
            return $this->getLot()->getAgeRecovery() + date_diff($dateRecovery, new \DateTime())->y;
        }

        return null;
    }

    public function getAgeRecovery(): ?int
    {
        return $this->ageRecovery;
    }

    public function setAgeRecovery(?int $ageRecovery): self
    {
        $this->ageRecovery = $ageRecovery;

        return $this;
    }

    public function getOutput(): ?bool
    {
        return $this->output;
    }

    public function setOutput(bool $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getOutputType(): ?OutputType
    {
        return $this->outputType;
    }

    public function setOutputType(?OutputType $outputType): self
    {
        $this->outputType = $outputType;

        return $this;
    }

    /**
     * @return Collection|TreeGrowth[]
     */
    public function getTreeGrowths(): Collection
    {
        return $this->treeGrowths;
    }

    public function addTreeGrowth(TreeGrowth $treeGrowth): self
    {
        if (!$this->treeGrowths->contains($treeGrowth)) {
            $this->treeGrowths[] = $treeGrowth;
            $treeGrowth->setTree($this);
        }

        return $this;
    }

    public function removeTreeGrowth(TreeGrowth $treeGrowth): self
    {
        if ($this->treeGrowths->removeElement($treeGrowth)) {
            // set the owning side to null (unless already changed)
            if ($treeGrowth->getTree() === $this) {
                $treeGrowth->setTree(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TreePicture[]
     */
    public function getTreePictures(): Collection
    {
        return $this->treePictures;
    }

    public function addTreePicture(TreePicture $treePicture): self
    {
        if (!$this->treePictures->contains($treePicture)) {
            $this->treePictures[] = $treePicture;
            $treePicture->setTree($this);
        }

        return $this;
    }

    public function removeTreePicture(TreePicture $treePicture): self
    {
        if ($this->treePictures->removeElement($treePicture)) {
            // set the owning side to null (unless already changed)
            if ($treePicture->getTree() === $this) {
                $treePicture->setTree(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistoryTree[]
     */
    public function getHistoryTrees(): Collection
    {
        return $this->historyTrees;
    }

    public function addHistoryTree(HistoryTree $historyTree): self
    {
        if (!$this->historyTrees->contains($historyTree)) {
            $this->historyTrees[] = $historyTree;
            $historyTree->setTree($this);
        }

        return $this;
    }

    public function removeHistoryTree(HistoryTree $historyTree): self
    {
        if ($this->historyTrees->removeElement($historyTree)) {
            // set the owning side to null (unless already changed)
            if ($historyTree->getTree() === $this) {
                $historyTree->setTree(null);
            }
        }

        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

        return $this;
    }

    public function getNursery(): ?Nursery
    {
        return $this->nursery;
    }

    public function setNursery(?Nursery $nursery): self
    {
        $this->nursery = $nursery;

        return $this;
    }

    /**
     * @return Collection|TreeWork[]
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(TreeWork $work): self
    {
        if (!$this->works->contains($work)) {
            $this->works[] = $work;
            $work->setTree($this);
        }

        return $this;
    }

    public function removeWork(TreeWork $work): self
    {
        if ($this->works->removeElement($work)) {
            // set the owning side to null (unless already changed)
            if ($work->getTree() === $this) {
                $work->setTree(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Style[]
     */
    public function getPotentialStyles(): Collection
    {
        return $this->potentialStyles;
    }

    public function addPotentialStyle(Style $potentialStyle): self
    {
        if (!$this->potentialStyles->contains($potentialStyle)) {
            $this->potentialStyles[] = $potentialStyle;
        }

        return $this;
    }

    public function removePotentialStyle(Style $potentialStyle): self
    {
        $this->potentialStyles->removeElement($potentialStyle);

        return $this;
    }

    public function getPotentialStylesStr(): string
    {
        $styles = $this->getPotentialStyles()->toArray();

        return implode(', ', array_map(
                static function ($key, Style $style) {
                    return $style->getName();
                },
                array_keys($styles),
                array_values($styles)
            )
        );
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getCategoriesStr(): string
    {
        $categories = $this->getCategories()->toArray();

        return implode(', ', array_map(
                static function ($key, Category $category) {
                    return $category->getName();
                },
                array_keys($categories),
                array_values($categories)
            )
        );
    }

    public function __toString(): string
    {
        return '#' . $this->getId();
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getNebariDiameter(): ?float
    {
        return $this->nebariDiameter;
    }

    public function setNebariDiameter(float $nebariDiameter): self
    {
        $this->nebariDiameter = $nebariDiameter;

        return $this;
    }

    public function getTrunkDiameter(): ?float
    {
        return $this->trunkDiameter;
    }

    public function setTrunkDiameter(float $trunkDiameter): self
    {
        $this->trunkDiameter = $trunkDiameter;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAdoption(): ?Adoption
    {
        return $this->adoption;
    }

    public function setAdoption(?Adoption $adoption): self
    {
        // set the owning side of the relation if necessary
        if ($adoption && $adoption->getTree() !== $this) {
            $adoption->setTree($this);
        }

        $this->adoption = $adoption;

        return $this;
    }

    public function getDeadTree(): ?DeadTree
    {
        return $this->deadTree;
    }

    public function setDeadTree(DeadTree $deadTree): self
    {
        // set the owning side of the relation if necessary
        if ($deadTree->getTree() !== $this) {
            $deadTree->setTree($this);
        }

        $this->deadTree = $deadTree;

        return $this;
    }

    public function getPotType(): ?PotType
    {
        return $this->potType;
    }

    public function setPotType(?PotType $potType): self
    {
        $this->potType = $potType;

        return $this;
    }

    public function getGreenhouse(): ?Greenhouse
    {
        return $this->greenhouse;
    }

    public function setGreenhouse(?Greenhouse $greenhouse): self
    {
        $this->greenhouse = $greenhouse;

        return $this;
    }

    public function getCultureTable(): ?CultureTable
    {
        return $this->cultureTable;
    }

    public function setCultureTable(?CultureTable $cultureTable): self
    {
        $this->cultureTable = $cultureTable;

        return $this;
    }

    public function getSegment(): ?Segment
    {
        return $this->segment;
    }

    public function setSegment(?Segment $segment): self
    {
        $this->segment = $segment;

        return $this;
    }

    public function getTableColumn(): ?TableColumn
    {
        return $this->tableColumn;
    }

    public function setTableColumn(?TableColumn $tableColumn): self
    {
        $this->tableColumn = $tableColumn;

        return $this;
    }

    public function getColumnRow(): ?ColumnRow
    {
        return $this->columnRow;
    }

    public function setColumnRow(?ColumnRow $columnRow): self
    {
        $this->columnRow = $columnRow;

        return $this;
    }

    public function getPosition(): ?ColumnRow
    {
        return $this->position;
    }

    public function setPosition(?ColumnRow $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|Observation[]
     */
    public function getObservations(): Collection
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation): self
    {
        if (!$this->observations->contains($observation)) {
            $this->observations[] = $observation;
            $observation->setTree($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): self
    {
        if ($this->observations->removeElement($observation)) {
            // set the owning side to null (unless already changed)
            if ($observation->getTree() === $this) {
                $observation->setTree(null);
            }
        }

        return $this;
    }

    public function getWorkingYear(): ?int
    {
        return $this->workingYear;
    }

    public function setWorkingYear(?int $workingYear): self
    {
        $this->workingYear = $workingYear;

        return $this;
    }
}
