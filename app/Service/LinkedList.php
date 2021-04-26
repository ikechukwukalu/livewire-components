<?php
namespace App\Service;

use App\Service\Node;

class LinkedList
{
    private $head;

    public function __construct()
    {
        $this->head = null;
    }

    public function insertAtFront($data): void
    {
        if ($this->head) {
            $newNode = new Node();
            $newNode->setData($data);
            $newNode->setNext($this->head);
            $this->head = $newNode;
        } else {
            $newNode = new Node();
            $newNode->setData($data);
            $this->head = $newNode;
        }
    }

    public function insertBeforeSpecificNode($data, $target): void
    {

        if ($this->head) {

            $currentNode = $this->head;
            $previousNode = null;

            while ($currentNode->getData() != $target && $currentNode->getNext() != null) {
                $previousNode = $currentNode;
                $currentNode = $currentNode->getNext();
            }

            if ($currentNode->getData() == $target) {
                $newNode = new Node();
                $newNode->setData($data);

                if ($previousNode) {
                    $previousNode->setNext($newNode);
                    $newNode->setNext($currentNode);
                } else {
                    $previousNode = $newNode;
                    $previousNode->setNext($currentNode);
                    $this->head = $previousNode;
                }
            }
        }
    }

    public function insertAtBack($data): void
    {

        $newNode = new Node();
        $newNode->setData($data);

        if ($this->head) {
            $currentNode = $this->head;
            while ($currentNode->getNext() != null) {
                $currentNode = $currentNode->getNext();
            }
            $currentNode->setNext($newNode);
        } else {
            $this->head = $newNode;
        }
    }

    public function insertAfterSpecificNode($data, $target): void
    {

        if ($this->head) {
            $currentNode = $this->head;
            while ($currentNode->getData() != $target && $currentNode->getNext() != null) {
                $currentNode = $currentNode->getNext();
            }

            if ($currentNode->getData() == $target) {
                $newNode = new Node();
                $newNode->setData($data);

                $currentNodeNext = $currentNode->getNext();
                $currentNode->setNext($newNode);
                $newNode->setNext($currentNodeNext);
            }
        }
    }

    public function deleteAtFront(): bool
    {

        if ($this->head) {
            $currentNode = $this->head;
            $this->head = $currentNode->getNext();
            unset($currentNode);
            return true;
        }
        return false;
    }

    public function deleteAtBack(): bool
    {

        if ($this->head) {
            $currentNode = $this->head;
            $previousNode = null;
            while ($currentNode->getNext() != null) {
                $previousNode = $currentNode;
                $currentNode = $currentNode->getNext();
            }
            if ($previousNode) {
                unset($currentNode);
                $previousNode->setNext(null);
            } else {
                $this->head = null;
            }
            return true;
        }
        return false;
    }

    public function deleteNode($target): bool
    {

        if ($this->head) {
            $currentNode = $this->head;
            $previousNode = null;

            while ($currentNode->getData() != $target && $currentNode->getNext() != null) {
                $previousNode = $currentNode;
                $currentNode = $currentNode->getNext();
            }

            if ($currentNode->getData() == $target) {
                if ($previousNode) {
                    $previousNode->setNext($currentNode->getNext());
                    unset($currentNode);
                } else {
                    $this->head = $currentNode->getNext();
                    unset($currentNode);
                }
                return true;
            }
        }

        return false;
    }

    public function printNode(): array
    {
        $currentNode = $this->head;
        $data = [];
        while ($currentNode != null) {
            $data[] = $currentNode->getData();
            $currentNode = $currentNode->getNext();
        }
        return $data;
    }
}