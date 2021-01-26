import { Component, ElementRef, Inject, OnInit } from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogConfig, MatDialogRef} from '@angular/material/dialog';
import {WordContext} from '../../../typing/app';

@Component({
  selector: 'app-word-context-dialog',
  templateUrl: './word-context-dialog.component.html',
  styleUrls: ['./word-context-dialog.component.scss']
})
export class WordContextDialogComponent implements OnInit {
  private readonly triggerElementRef: ElementRef;
  public readonly word: string;
  public readonly contextList: WordContext[];

  constructor(
    private dialogRef: MatDialogRef<WordContextDialogComponent>,
    @Inject(MAT_DIALOG_DATA) data: { trigger: ElementRef, word: string, contextList: WordContext[] }
  ) {
    this.triggerElementRef = data.trigger;
    this.word = data.word;
    this.contextList = data.contextList;
    console.log(this.contextList)
  }

  ngOnInit(): void {
    const matDialogConfig: MatDialogConfig = new MatDialogConfig();
    const rect = this.triggerElementRef.nativeElement.getBoundingClientRect();

    matDialogConfig.position = { left: `${rect.left}px`, top: `${rect.bottom - 50}px` };

    this.dialogRef.updateSize(matDialogConfig.width, matDialogConfig.height);
    this.dialogRef.updatePosition(matDialogConfig.position);
  }

  onNoClick(): void {
    this.dialogRef.close();
  }
}
