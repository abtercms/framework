<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

interface IResourcable
{
    /**
     * @param string|null $resource
     *
     * @return $this
     */
    public function setResource(?string $resource): IResourcable;

    /**
     * @return string|null
     */
    public function getResource(): ?string;

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole(string $role): IResourcable;

    /**
     * @return string
     */
    public function getRole(): string;

    /**
     * @return $this
     */
    public function disable(): IResourcable;
}
