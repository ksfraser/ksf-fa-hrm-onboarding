<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Onboarding\Tests\Unit;
use PHPUnit\Framework\TestCase;
use Ksfraser\FrontAccounting\HRM\Onboarding\DTO\OnboardingDTO;
class OnboardingDtoTest extends TestCase
{
    public function testDtoRoundTripsThroughArray(): void {
        $dto = new OnboardingDTO(5, 10, 'Sue Jones', 'new_hire', 'Setup email', '1', '2026-10-05 09:00:00', 'Done', '2026-10-05', '2026-10-01 09:00:00', '2026-10-05 09:00:00');
        $arr = $dto->toArray();
        $this->assertSame(5, $arr['checklist_id']);
        $this->assertSame(10, $arr['person_id']);
        $this->assertSame('Sue Jones', $arr['person_name']);
        $this->assertSame('new_hire', $arr['checklist_type']);
        $this->assertSame('Setup email', $arr['item']);
        $this->assertSame('1', $arr['completed']);
        $dto2 = OnboardingDTO::fromArray($arr);
        $this->assertSame($dto->checklist_id, $dto2->checklist_id);
        $this->assertSame($dto->completed, $dto2->completed);
        $this->assertSame($dto->item, $dto2->item);
    }
    public function testDisplayLabelUsesPersonAndItemWhenSet(): void {
        $dto = new OnboardingDTO(1, 2, 'Sue Jones', 'new_hire', 'Setup email');
        $this->assertStringContainsString('Sue Jones', $dto->getDisplayLabel());
        $this->assertStringContainsString('Setup email', $dto->getDisplayLabel());
    }
    public function testDisplayLabelFallsBackToId(): void {
        $dto = new OnboardingDTO(9, 2, '', '', '');
        $this->assertStringContainsString('Onboarding Checklist #9', $dto->getDisplayLabel());
    }
}
