import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TicketHandleComponent } from './ticket-handle.component';

describe('TicketHandleComponent', () => {
  let component: TicketHandleComponent;
  let fixture: ComponentFixture<TicketHandleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [TicketHandleComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(TicketHandleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
