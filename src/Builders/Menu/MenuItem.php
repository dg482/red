<?php

namespace Dg482\Red\Builders\Menu;

/**
 * Class MenuItem
 * @package App\Admin\Builder\Menu
 */
class MenuItem
{
    /** @var int */
    private int $id = 0;

    /** @var string */
    private string $name = '';

    /** @var int */
    private int $parentId = 0;

    /** @var string */
    protected string $title = '';

    /** @var string */
    protected string $icon = '';

    /** @var string */
    protected string $href = '';

    /** @var array */
    protected array $badge = [];

    /** @var array[MenuItem] */
    protected array $child = [];

    /** @var array[string] */
    protected array $permission = [
        'user', 'admin',
    ];

    /** @var array */
    protected array $meta = [
        'show' => true,
    ];

    /** @var string default ui component */
    protected string $component = 'RouteView';

    /** @var string */
    protected string $redirect = '';

    /** @var bool */
    protected bool $hidden = false;

    /**
     * MenuItem constructor.
     * @param  int  $id
     */
    public function __construct(int $id = 0)
    {
        $this->id = $id ?: (microtime(true) + rand(5, 5000));
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     * @return MenuItem
     */
    public function setTitle(string $title): MenuItem
    {
        $this->title = $title;

        $this->meta['title'] = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param  string  $icon
     * @return MenuItem
     */
    public function setIcon(string $icon): MenuItem
    {
        $this->icon = $icon;

        $this->meta['icon'] = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param  string  $href
     * @return MenuItem
     */
    public function setHref(string $href): MenuItem
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @return array
     */
    public function getBadge(): array
    {
        return $this->badge;
    }

    /**
     * @param  array  $badge
     * @return MenuItem
     */
    public function setBadge(array $badge): MenuItem
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return array
     */
    public function getChild(): array
    {
        return $this->child;
    }

    /**
     * @param  MenuItem  $child
     * @return MenuItem
     */
    public function setChild(MenuItem $child): MenuItem
    {
        $this->child[] = $child->setParentId($this->getId());

        return $this;
    }

    /**
     * @param  string  $title
     * @param  string  $href
     * @return $this
     */
    public function addChild(string $title, string $href): MenuItem
    {
        $this->setChild((new MenuItem)
            ->setTitle($title)
            ->setHref($href))
            ->setParentId($this->getId());

        return $this;
    }

    /**
     * @return array
     */
    public function getPermission(): array
    {
        return $this->permission;
    }

    /**
     * @param  array  $permission
     * @return MenuItem
     */
    public function setPermission(array $permission): MenuItem
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @param  int  $role
     * @return bool
     */
    protected function checkPermission(int $role): bool
    {
        return (isset($this->permission[$role]));
    }

    /**
     * @return array
     */
    protected function getChildItems(): array
    {
        $result = [];
        array_map(function (MenuItem $item) use (&$result) {
            array_push($result, $item->getData());
        }, $this->getChild());

        return $result;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return array_filter([
            'id' => $this->getId(),
            'name' => $this->getName(),
            'parentId' => $this->getParentId(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'href' => $this->getHref(),
            'path' => $this->getHref(),
            'component' => $this->getComponent(),
            'redirect' => $this->getRedirect(),
            'child' => $this->getChildItems(),
            'badge' => $this->getBadge(),
            'meta' => $this->getMeta(),
            'hidden' => $this->isHidden(),
        ], function ($value) {
            return !empty($value) || $value === 0;
        });
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param  int  $id
     * @return MenuItem
     */
    public function setId(int $id): MenuItem
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param  int  $parentId
     * @return MenuItem
     */
    public function setParentId(int $parentId): MenuItem
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param  array  $meta
     * @return MenuItem
     */
    public function setMeta(array $meta): MenuItem
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * @param  string  $component
     * @return MenuItem
     */
    public function setComponent(string $component): MenuItem
    {
        $this->component = $component;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @param  string  $redirect
     * @return MenuItem
     */
    public function setRedirect(string $redirect): MenuItem
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     * @return MenuItem
     */
    public function setName(string $name): MenuItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param  bool  $hidden
     * @return MenuItem
     */
    public function setHidden(bool $hidden): MenuItem
    {
        $this->hidden = $hidden;

        return $this;
    }
}
